<?php

namespace App\Filament\Resources\RapportEtats\Pages;

use App\Filament\Resources\RapportEtats\RapportEtatsResource;
use App\Models\Dossier;
use App\Models\RapportEtat;
use App\Services\RapportService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRapportEtat extends EditRecord
{
    protected static string $resource = RapportEtatsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('apercu_html')
                ->label('Aperçu HTML')
                ->icon('heroicon-o-eye')
                ->form([$this->dossierPicker()])
                ->action(function (array $data, RapportEtat $record) {
                    $this->redirect(route('admin.rapports-etats.apercu', [
                        'etat' => $record,
                        'dossier' => $data['dossier_id'],
                    ]));
                }),
            Action::make('generer_pdf')
                ->label('Générer PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->form([$this->dossierPicker()])
                ->action(function (array $data, RapportEtat $record) {
                    $this->redirect(route('admin.rapports-etats.pdf', [
                        'etat' => $record,
                        'dossier' => $data['dossier_id'],
                    ]));
                }),
            Action::make('impression_masse')
                ->label('Impression en masse (batch)')
                ->icon('heroicon-o-printer')
                ->form([
                    Select::make('dossiers_ids')
                        ->label('Dossiers cibles (vide = les N premiers)')
                        ->multiple()
                        ->searchable()
                        ->options(fn () => $this->dossierOptions()),
                    TextInput::make('limite')
                        ->label('Limite si aucune sélection')
                        ->numeric()
                        ->default(50)
                        ->minValue(1),
                ])
                ->requiresConfirmation()
                ->action(function (array $data, RapportEtat $record) {
                    $query = Dossier::query()
                        ->whereNotNull('annee_inscription')
                        ->with('doctorant');

                    $dossiers = ! empty($data['dossiers_ids'])
                        ? $query->whereIn('id', $data['dossiers_ids'])->get()
                        : $query->orderBy('id')->limit((int) ($data['limite'] ?? 50))->get();

                    $resultat = app(RapportService::class)->genererEnMasse($record, $dossiers, auth()->user());

                    Notification::make()
                        ->success(sprintf(
                            'Impression en masse terminée : %d/%d états générés et archivés dans les mallettes.',
                            $resultat['reussites'],
                            $resultat['total'],
                        ))
                        ->send();
                }),
        ];
    }

    protected function dossierPicker(): Select
    {
        return Select::make('dossier_id')
            ->label('Dossier (doctorant) cible')
            ->searchable()
            ->required()
            ->options(fn () => $this->dossierOptions());
    }

    protected function dossierOptions(): array
    {
        return Dossier::query()
            ->with('doctorant')
            ->latest('id')
            ->limit(500)
            ->get()
            ->mapWithKeys(fn (Dossier $dossier) => [
                $dossier->getKey() => ($dossier->doctorant?->name ?? '—').' · '.$dossier->objet,
            ])
            ->all();
    }
}
