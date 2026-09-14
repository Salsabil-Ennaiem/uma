<?php

namespace App\Services;

use App\Enums\InvitationStatut;
use App\Enums\ReunionStatut;
use App\Models\AuditLog;
use App\Models\Commission;
use App\Models\Invitation;
use App\Models\Reunion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use SalsabilEnnaiem\PvModule\Services\PvService;

/**
 * Logique métier des réunions de commission.
 * Responsabilités :
 *  - auto-ajout des membres de la commission à la création (CDC §réunions) ;
 *  - transitions de statut validées (brouillon → planifiée → en cours → terminée / annulée) ;
 *  - synchronisation des invitations (ajout/retrait) ;
 *  - génération et envoi du PV via le package PvService.
 */
class ReunionService
{
    public function __construct(
        private PvService $pvService,
        private NotificationService $notifications,
    ) {}

    public function create(array $data, User $actor): Reunion
    {
        return DB::transaction(function () use ($data, $actor) {
            $reunion = new Reunion($data);
            $reunion->created_by = $actor->getKey();
            $reunion->updated_by = $actor->getKey();
            $reunion->statut = ReunionStatut::Brouillon;
            $reunion->save();

            // ODJ pré-remplie depuis le modèle paramétrable si attendu.
            if ($reunion->odjTemplate && blank($reunion->ordre_du_jour)) {
                $reunion->ordre_du_jour = $reunion->odjTemplate->contenu;
                $reunion->save();
            }

            // Auto-ajout des membres de la commission (CDC : choix de la discipline).
            $this->syncMembersFromCommission($reunion);

            AuditLog::log('reunion.create', $reunion, null, $reunion->only([
                'commission_id', 'objet', 'date_debut', 'statut',
            ]));

            return $reunion;
        });
    }

    /**
     * Ajoute automatiquement les membres (hors président) de la commission.
     */
    public function syncMembersFromCommission(Reunion $reunion): array
    {
        $commission = $reunion->commission;

        if ($commission === null) {
            return [];
        }

        $added = [];

        $users = $commission->membres()
            ->when($commission->president_id, fn ($q) => $q->where('users.id', '!=', $commission->president_id))
            ->pluck('users.id');

        foreach ($users as $userId) {
            $this->addParticipant($reunion, (int) $userId);

            $added[] = (int) $userId;
        }

        return $added;
    }

    public function addParticipant(Reunion $reunion, int $userId): Invitation
    {
        return Invitation::firstOrCreate(
            ['reunion_id' => $reunion->getKey(), 'participant_id' => $userId],
            ['statut' => InvitationStatut::EnAttente],
        );
    }

    public function removeParticipant(Reunion $reunion, int $userId): bool
    {
        return (bool) $reunion->invitations()
            ->where('participant_id', $userId)
            ->delete();
    }

    /**
     * Synchronise la liste des invités (par user_id). Les invitations absentes
     * de la liste cible sont retirées, les nouvelles sont créées.
     */
    public function syncParticipants(Reunion $reunion, array $userIds): array
    {
        return DB::transaction(function () use ($reunion, $userIds) {
            $current = $reunion->invitations()
                ->whereNotNull('participant_id')
                ->pluck('participant_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $target = array_values(array_unique(array_map('intval', $userIds)));

            $toRemove = array_diff($current, $target);
            foreach ($toRemove as $userId) {
                $this->removeParticipant($reunion, $userId);
            }

            $toAdd = array_diff($target, $current);
            foreach ($toAdd as $userId) {
                $this->addParticipant($reunion, $userId);
            }

            AuditLog::log('reunion.invitations.sync', $reunion, ['before' => $current], ['after' => $target]);

            return $target;
        });
    }

    /**
     * Transition de statut avec garde des transitions autorisées.
     */
    public function transition(Reunion $reunion, ReunionStatut $target, User $actor): Reunion
    {
        $current = $reunion->statut;

        if ($current === $target) {
            return $reunion;
        }

        if (! $current->canTransitionTo($target)) {
            throw new \DomainException(
                "Transition non autorisée : {$current->value} → {$target->value}."
            );
        }

        $reunion->statut = $target;
        $reunion->updated_by = $actor->getKey();
        $reunion->save();

        AuditLog::log('reunion.statut', $reunion, ['statut' => $current->value], ['statut' => $target->value]);

        // Convocation automatique par mail à la planification.
        if ($target === ReunionStatut::Planifiee) {
            $this->notifications->sendReunionPlanifiee($reunion);
        }

        if ($target === ReunionStatut::Terminee) {
            $this->notifications->sendReunionTerminee($reunion);
        }

        return $reunion;
    }

    /**
     * Génère le PV de la réunion via le package.
     * Source rattachée : `reunion/{id}` — la RBAC du package s'applique ensuite.
     */
    public function genererPv(Reunion $reunion, User $actor, array $contenu = []): mixed
    {
        if (! $reunion->estPassee()) {
            throw new \DomainException('Le PV ne peut être généré qu\'après la fin de la réunion.');
        }

        $presents = $reunion->presences()->present()->pluck('participant_id')->filter()->all();

        $pv = $this->pvService->store(
            [
                'titre' => 'PV — '.$reunion->objet,
                'contenu' => $contenu ?: $this->pvContenuParDefaut($reunion),
                'type' => 'pv',
            ],
            $actor,
            'reunion',
            $reunion->getKey(),
        );

        if (! empty($presents)) {
            $this->pvService->send($pv, $presents);
        }

        AuditLog::log('reunion.pv.generer', $reunion, null, ['pv_id' => $pv->getKey(), 'presents' => $presents]);

        return $pv;
    }

    protected function pvContenuParDefaut(Reunion $reunion): array
    {
        $lignes = [
            'Réunion de la commission : '.$reunion->commission?->nom,
            'Objet : '.$reunion->objet,
            'Date : '.optional($reunion->date_debut)->translatedFormat('d/m/Y H:i'),
        ];

        if ($reunion->decisions()->exists()) {
            $lignes[] = '';
            $lignes[] = 'Décisions :';
            foreach ($reunion->decisions as $decision) {
                $lignes[] = '- ['.$decision->dossier?->objet.'] : '.$decision->label;
            }
        }

        return $lignes;
    }
}
