<?php

namespace App\Filament\Resources\Reunions\Pages;

use App\Enums\ReunionStatut;
use App\Filament\Resources\Reunions\ReunionResource;
use App\Models\Reunion;
use App\Services\DecisionService;
use App\Services\ReunionService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class EditReunion extends EditRecord
{
    protected static string $resource = ReunionResource::class;

    protected function getHeaderActions(): array
    {
        $reunion = $this->record;

        return [
            Action::make('presences')
                ->label('Présences')
                ->icon('heroicon-o-user-group')
                ->url(fn () => ReunionResource::getUrl('presences', ['record' => $reunion]))
                ->visible(fn () => auth()->user()->can('enregistrerPresence', $reunion)),

            Action::make('decisions')
                ->label('Décisions')
                ->icon('heroicon-o-clipboard-document-check')
                ->url(fn () => ReunionResource::getUrl('decisions', ['record' => $reunion]))
                ->visible(fn () => auth()->user()->can('gererDecisions', $reunion)),

            Action::make('export_decisions')
                ->label('Exporter les décisions (CSV)')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (Reunion $record) {
                    $csv = app(DecisionService::class)->exportCsv($record);

                    return StreamedResponse::create(
                        static fn () => print ($csv),
                        200,
                        [
                            'Content-Type' => 'text/csv; charset=UTF-8',
                            'Content-Disposition' => 'attachment; filename="decisions_reunion_'.$record->getKey().'_'.now()->format('Ymd').'.csv"',
                        ],
                    );
                })
                ->visible(fn (Reunion $record) => $record->decisions()->exists() && auth()->user()->can('gererDecisions', $record)),

            ActionGroup::make([
                $this->statutAction('planifier', 'Planifier', 'heroicon-o-paper-airplane'),
                $this->statutAction('demarrer', 'Démarrer la réunion', 'heroicon-o-play'),
                $this->statutAction('terminer', 'Terminer', 'heroicon-o-check-circle'),
                $this->statutAction('annuler', 'Annuler', 'heroicon-o-x-circle', true),
            ])->label('Statut')
                ->icon('heroicon-o-arrows-right-left')
                ->visible(fn () => auth()->user()->can('update', $reunion)),

            Action::make('generer_pv')
                ->label('Générer le PV')
                ->icon('heroicon-o-document-text')
                ->form([
                    Textarea::make('contenu')
                        ->label('Contenu du PV (optionnel)')
                        ->helperText('Laissez vide pour utiliser le contenu par défaut (objet + décisions).'),
                ])
                ->requiresConfirmation()
                ->visible(fn () => auth()->user()->can('genererPv', $reunion))
                ->action(function (array $data, Reunion $record) {
                    try {
                        $pv = app(ReunionService::class)->genererPv($record, auth()->user(), $data['contenu'] ?? []);

                        Notification::make()
                            ->success('PV généré et envoyé pour signature aux présents.')
                            ->send();

                        $this->redirect('/admin/documents');
                    } catch (Throwable $e) {
                        Notification::make()->danger($e->getMessage())->send();
                    }
                }),
        ];
    }

    protected function statutAction(string $method, string $label, string $icon, bool $danger = false): Action
    {
        return Action::make('statut_'.$method)
            ->label($label)
            ->icon($icon)
            ->color($danger ? 'danger' : 'primary')
            ->requiresConfirmation()
            ->visible(function (Reunion $record) use ($method, $danger) {
                $cible = match ($method) {
                    'planifier' => ReunionStatut::Planifiee,
                    'demarrer' => ReunionStatut::EnCours,
                    'terminer' => ReunionStatut::Terminee,
                    'annuler' => ReunionStatut::Annulee,
                };

                if ($danger) {
                    return $record->statut !== $cible && auth()->user()->can('annuler', $record);
                }

                return $record->statut !== $cible
                    && $record->statut->canTransitionTo($cible)
                    && auth()->user()->can($method, $record);
            })
            ->action(function (Reunion $record) use ($method) {
                $cible = match ($method) {
                    'planifier' => ReunionStatut::Planifiee,
                    'demarrer' => ReunionStatut::EnCours,
                    'terminer' => ReunionStatut::Terminee,
                    'annuler' => ReunionStatut::Annulee,
                };

                app(ReunionService::class)->transition($record, $cible, auth()->user());

                Notification::make()->success('Réunion mise à jour : '.$cible->label().'.')->send();
            });
    }
}
