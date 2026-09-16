<?php

namespace App\Services;

use App\Enums\DossierStatut;
use App\Models\AuditLog;
use App\Models\Decision;
use App\Models\DecisionTemplate;
use App\Models\Dossier;
use App\Models\Reunion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Saisie et export des décisions de réunion.
 * - Snapshot du modèle paramétrable (label + email) dans la décision (CDC).
 * - Export décisions → projet de PV (CSV maison, zéro dépendance).
 */
class DecisionService
{
    public function record(
        Reunion $reunion,
        Dossier $dossier,
        DecisionTemplate $template,
        User $decideur,
        ?string $anneeInscription = null,
    ): Decision {
        return DB::transaction(function () use ($reunion, $dossier, $template, $decideur, $anneeInscription) {
            $decision = Decision::create([
                'reunion_id' => $reunion->getKey(),
                'dossier_id' => $dossier->getKey(),
                'decision_template_id' => $template->getKey(),
                'label' => $template->label,
                'email_subject' => $template->email_subject,
                'email_body' => $template->email_body,
                'annee_inscription' => $anneeInscription ?? $dossier->annee_inscription,
                'decided_by' => $decideur->getKey(),
            ]);

            $dossier->statut = DossierStatut::Traite;
            $dossier->save();

            AuditLog::log('decision.create', $reunion, null, [
                'decision_id' => $decision->getKey(),
                'dossier_id' => $dossier->getKey(),
                'label' => $template->label,
            ]);

            return $decision;
        });
    }

    /**
     * Export CSV des décisions d'une réunion vers un fichier téléchargeable.
     * Contenu aligné sur le « tableau des décisions » attendu dans le PV.
     */
    public function exportCsv(Reunion $reunion): string
    {
        $lines = [
            ['Dossier', 'Doctorant', 'Année', 'Décision', 'Date'],
        ];

        foreach ($reunion->decisions()->with(['dossier.doctorant'])->orderBy('id')->get() as $decision) {
            $lines[] = [
                $decision->dossier?->objet ?? '',
                $decision->dossier?->doctorant?->name ?? '',
                $decision->annee_inscription ?? '',
                $decision->label,
                optional($decision->created_at)->format('d/m/Y') ?? '',
            ];
        }

        $csv = implode("\r\n", array_map(
            fn (array $row) => implode(';', array_map(fn ($cell) => '"'.str_replace('"', '""', (string) $cell).'"', $row)),
            $lines,
        ));

        return $csv;
    }

    public function storeCsv(Reunion $reunion, string $disk = 'local'): string
    {
        $aujourdhui = now()->format('Y-m-d');
        $path = "exports/decisions_reunion_{$reunion->getKey()}_{$aujourdhui}.csv";

        Storage::disk($disk)->put($path, $this->exportCsv($reunion));

        return $path;
    }

    /**
     * Contenu structuré pour alimenter le projet de PV via le package.
     */
    public function pvContenu(Reunion $reunion): array
    {
        $lignes = [
            'Projet de PV — '.$reunion->objet,
            'Commission : '.$reunion->commission?->nom,
            '',
            'Décisions adoptées :',
            '',
        ];

        foreach ($reunion->decisions()->with('dossier.doctorant')->get() as $decision) {
            $lignes[] = '- '.($decision->dossier?->doctorant?->name ?? $decision->dossier?->objet ?? 'Dossier')
                .' (année '.($decision->annee_inscription ?? '—').') : '.$decision->label;
        }

        return $lignes;
    }
}
