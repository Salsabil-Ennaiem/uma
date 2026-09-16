<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\RapportEtat;
use App\Services\RapportService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Rendu « rapport / état » (HTML ou PDF) via le moteur du package.
 * Aucune sortie maison : tout passe par PvTemplate + PdfService.
 */
class RapportController extends Controller
{
    public function apercu(RapportEtat $etat, Request $request): Response
    {
        $this->authorize('generer', $etat);

        $dossier = Dossier::findOrFail((int) $request->query('dossier'));

        $html = app(RapportService::class)->apercuHtml($etat, $dossier, $request->user());

        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8');
    }

    public function pdf(RapportEtat $etat, Request $request): Response
    {
        $this->authorize('generer', $etat);

        $dossier = Dossier::findOrFail((int) $request->query('dossier'));

        $resultat = app(RapportService::class)->generer(
            $etat,
            $dossier,
            $request->user(),
            false,
        );

        return response($resultat['pdf'])
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="'.Str::slug($etat->label).'-'.Str::slug($dossier->objet).'.pdf"');
    }
}
