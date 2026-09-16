<?php

namespace App\Services;

use App\Models\Dossier;
use App\Models\RapportEtat;
use App\Models\User;
use DomainException;
use SalsabilEnnaiem\PvModule\Models\Pv;
use SalsabilEnnaiem\PvModule\Services\PdfService;
use SalsabilEnnaiem\PvModule\Services\PvService;
use Throwable;

/**
 * Module « rapports et états » (CDC §1.13).
 * - Configuration par état : marges, orientation, format papier, en-tête.
 * - Rendu exclusivement via le moteur du package (PvTemplate + PdfService) ;
 * - rendu HTML (aperçu) ou PDF au choix, impression en masse (batch).
 */
class RapportService
{
    public function __construct(
        private PvService $pvService,
        private PdfService $pdfService,
        private ArchiveService $archive,
        private AuditLogger $audit,
    ) {}

    public function contenu(RapportEtat $etat, Dossier $dossier): array
    {
        $doctorant = $dossier->doctorant;

        return [
            $etat->label,
            '',
            'Doctorant : '.($doctorant?->name ?? '—'),
            "Année d'inscription : ".($dossier->annee_inscription ?? '—'),
            'Dossier : '.$dossier->objet,
            'Commission : '.$dossier->commission?->nom,
        ];
    }

    protected function persister(RapportEtat $etat, Dossier $dossier, User $actor): Pv
    {
        if (! $etat->is_active) {
            throw new DomainException("L'état « {$etat->label} » est désactivé.");
        }

        return $this->pvService->store([
            'titre' => $etat->label.' — '.($dossier->doctorant?->name ?? $dossier->objet),
            'contenu' => $this->contenu($etat, $dossier),
            'type' => $etat->pv_type,
        ], $actor, 'rapport_etat', $etat->getKey());
    }

    /**
     * Aperçu HTML pré-rendu par le moteur du package (aucune sortie maison).
     */
    public function apercuHtml(RapportEtat $etat, Dossier $dossier, User $actor): string
    {
        $pv = $this->persister($etat, $dossier, $actor);

        return $this->pdfService->renderHtml($this->pdfService->pvPayload($pv));
    }

    /**
     * Génère le PDF (package) et archive la pièce dans le dossier (mallette).
     */
    public function generer(RapportEtat $etat, Dossier $dossier, User $actor, bool $archiver = true): array
    {
        $pv = $this->persister($etat, $dossier, $actor);

        $payload = $this->pdfService->pvPayload($pv);
        $mpdf = $this->pdfService->mpdf(
            $etat->orientation ?: ($payload['orientation'] ?? 'portrait'),
            $etat->margesEffectives(),
        );

        if (filled($etat->en_tete)) {
            $mpdf->SetHeader($etat->en_tete);
        }

        $mpdf->WriteHTML($this->pdfService->renderHtml($payload));
        $pdf = $mpdf->Output('', 'S');

        $document = null;
        if ($archiver) {
            $document = $this->archive->archiverContenu(
                $dossier,
                $etat->pv_type,
                $etat->label.' — '.($dossier->doctorant?->name ?? $dossier->objet),
                $pdf,
                'pdf',
                null,
                ['pv_id' => $pv->getKey(), 'rapport_etat_id' => $etat->getKey()],
                actor: $actor,
            );
        }

        $this->audit->log('rapport.generer', $etat, null, [
            'pv_id' => $pv->getKey(),
            'dossier_id' => $dossier->getKey(),
            'document_id' => $document?->getKey(),
        ], $actor);

        return ['pv' => $pv, 'pdf' => $pdf, 'document' => $document];
    }

    /**
     * Impression en masse pour un lot de dossiers (ex. 50 attestations).
     * Une génération échouée n'interrompt pas le lot.
     */
    public function genererEnMasse(RapportEtat $etat, iterable $dossiers, User $actor): array
    {
        $documents = [];
        $successes = 0;
        $echecs = 0;
        $erreurs = [];

        foreach ($dossiers as $dossier) {
            try {
                $result = $this->generer($etat, $dossier, $actor);
                $successes++;
                if ($result['document'] !== null) {
                    $documents[] = $result['document'];
                }
            } catch (Throwable $e) {
                $echecs++;
                $erreurs[$dossier->getKey()] = $e->getMessage();
            }
        }

        return [
            'total' => $successes + $echecs,
            'reussites' => $successes,
            'echecs' => $echecs,
            'documents' => $documents,
            'erreurs' => $erreurs,
        ];
    }
}
