<?php

namespace App\Services;

use App\Mail\WorkflowStateChanged;
use App\Models\Dossier;
use App\Models\RapportEtat;
use App\Models\Reclamation;
use App\Models\Reservation;
use App\Models\User;
use App\Models\WorkflowInstance;
use App\Models\WorkflowTransition;
use App\Notifications\WorkflowTransitioned;
use DomainException;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Actions de bord déclarées dans les transitions (colonne `actions`).
 * Chaque clé correspond à une méthode du moteur — la déclaration des actions
 * reste paramétrable en base, l'implémentation vit dans le moteur (jamais
 * dans les écrans Filament).
 */
class WorkflowActions
{
    public function __construct(
        private RapportService $rapports,
        private ArchiveService $archive,
        private AuditLogger $audit,
    ) {}

    /**
     * Exécute une action par sa clé de déclaration.
     */
    public function run(string $key, WorkflowInstance $instance, WorkflowTransition $transition, array $payload = [], ?User $actor = null): mixed
    {
        return match ($key) {
            'attestation.generer' => $this->genererAttestation($instance, $actor),
            'reservation.creer' => $this->creerReservation($instance, $actor),
            'reclamation.actualiser' => $this->actualiserReclamation($instance),
            default => $this->traceAction($key, $instance, $transition, $actor),
        };
    }

    public function notifyUser(User $user, WorkflowInstance $instance, string $message): void
    {
        try {
            $user->notify(new WorkflowTransitioned($instance, $message));
            Mail::to($user->email)->queue(new WorkflowStateChanged($instance, $message));
        } catch (Throwable $e) {
            report($e);
        }
    }

    /**
     * Action « attestation.generer » : génère l'attestation FR/AR via le moteur
     * du package et l'archive dans la mallette (intégration P7).
     */
    protected function genererAttestation(WorkflowInstance $instance, ?User $actor): ?object
    {
        $subject = $instance->subject;

        if (! $subject instanceof Dossier) {
            throw new DomainException('L\'action attestation.generer exige un sujet Dossier.');
        }

        $etat = RapportEtat::query()
            ->where('is_active', true)
            ->where('type', 'etat')
            ->first();

        if ($etat === null) {
            return null;
        }

        $actor ??= $instance->creator;

        $resultat = $this->rapports->generer($etat, $subject, $actor);

        $this->audit->log('workflow.action.attestation', $instance, null, [
            'document_id' => $resultat['document']?->getKey(),
        ], $actor);

        return $resultat['document'];
    }

    /**
     * Action « reclamation.actualiser » : reflète l'état du workflow sur la
     * réclamation (statut courant, clôture).
     */
    protected function actualiserReclamation(WorkflowInstance $instance): Reclamation
    {
        $reclamation = $instance->subject;

        if (! $reclamation instanceof Reclamation) {
            throw new DomainException('L\'action reclamation.actualiser exige un sujet Reclamation.');
        }

        $reclamation->statut = $instance->current_state;

        if ($instance->definition->isTerminalState($instance->current_state)) {
            $reclamation->closed_at = $reclamation->closed_at ?? now();
        }

        $reclamation->save();

        return $reclamation;
    }

    protected function creerReservation(WorkflowInstance $instance, ?User $actor): Reservation
    {
        $data = (array) $instance->data;
        $debut = $data['soutenance_date'] ?? null;
        $fin = $data['soutenance_fin'] ?? null;
        $salle = $data['salle'] ?? null;

        if (! $debut || ! $fin) {
            throw new DomainException('Une date de soutenance est requise pour la réservation.');
        }

        $reservation = new Reservation([
            'type' => Reservation::TYPE_SALLE,
            'salle' => $salle,
            'date_debut' => $debut,
            'date_fin' => $fin,
            'objet' => 'Soutenance — workflow '.$instance->definition->code,
            'created_by' => $actor?->getKey(),
        ]);
        $reservation->save();

        foreach ((array) ($data['jury_membres'] ?? []) as $membreId) {
            Reservation::create([
                'type' => Reservation::TYPE_JURY,
                'membre_id' => (int) $membreId,
                'date_debut' => $debut,
                'date_fin' => $fin,
                'objet' => 'Jury — workflow '.$instance->definition->code,
                'created_by' => $actor?->getKey(),
            ]);
        }

        return $reservation;
    }

    protected function traceAction(string $key, WorkflowInstance $instance, WorkflowTransition $transition, ?User $actor): void
    {
        $this->audit->log('workflow.action.'.$key, $instance, null, [
            'transition' => $transition->code,
        ], $actor);
    }
}