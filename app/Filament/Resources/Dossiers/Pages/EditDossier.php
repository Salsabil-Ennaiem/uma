<?php

namespace App\Filament\Resources\Dossiers\Pages;

use App\Filament\Resources\Dossiers\DossierResource;
use App\Models\WorkflowDefinition;
use App\Models\WorkflowInstance;
use App\Models\WorkflowTransition;
use App\Services\WorkflowEngine;
use DomainException;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDossier extends EditRecord
{
    protected static string $resource = DossierResource::class;

    public function getHeaderActions(): array
    {
        $actions = [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];

        $instance = $this->record->activeWorkflow();

        if ($instance === null) {
            $actions[] = $this->demarrerWorkflowAction();

            return $actions;
        }

        foreach ($this->engine()->availableTransitions($instance, auth()->user()) as $transition) {
            $actions[] = $this->transitionAction($transition);
        }

        $actions[] = $this->historiqueAction($instance);

        return $actions;
    }

    protected function demarrerWorkflowAction(): Action
    {
        return Action::make('demarrerWorkflow')
            ->label('Démarrer le workflow')
            ->icon('heroicon-o-play')
            ->color('primary')
            ->form([
                Select::make('niveau')
                    ->label('Niveau d\'inscription')
                    ->options([
                        1 => '1ʳᵉ année',
                        2 => '2ᵉ année',
                        3 => '3ᵉ année',
                        4 => '4ᵉ année',
                        5 => '5ᵉ année',
                    ])
                    ->default($this->niveauParDefaut())
                    ->required(),
            ])
            ->action(function (array $data) {
                $niveau = (int) $data['niveau'];
                $code = $niveau >= 2 ? 'reinscription' : 'inscription';
                $definition = WorkflowDefinition::query()->where('code', $code)->firstOrFail();

                $this->engine()->start($definition, $this->record, ['niveau' => $niveau], auth()->user());

                $this->rafraichirNotification('Workflow démarré.');
            })
            ->visible(fn () => $this->peutPiloter());
    }

    protected function transitionAction(WorkflowTransition $transition): Action
    {
        return Action::make('transition_'.$transition->code)
            ->label($transition->label ?? $transition->code)
            ->color('primary')
            ->form($this->champsDeTransition($transition->code))
            ->modalHeading($transition->label ?? $transition->code)
            ->requiresConfirmation()
            ->action(function (array $data) use ($transition) {
                $instance = $this->record->activeWorkflow();

                if ($instance === null || $instance->current_state !== $transition->from_state) {
                    $this->rafraichirNotification(
                        'Le workflow a changé d\'état entre-temps. Actualisez la page.',
                        danger: true,
                    );

                    return;
                }

                try {
                    $this->engine()->apply($instance, $transition, $data, auth()->user());
                } catch (DomainException $e) {
                    $this->rafraichirNotification($e->getMessage(), danger: true);

                    return;
                }

                $this->rafraichirNotification('Transition « '.$transition->code.' » appliquée.');
            })
            ->visible(fn () => $this->peutPiloter());
    }

    protected function historiqueAction(WorkflowInstance $instance): Action
    {
        return Action::make('historiqueWorkflow')
            ->label('Historique du workflow')
            ->icon('heroicon-o-clock')
            ->color('gray')
            ->modalContent(view('filament.resources.dossiers.workflow-historique', [
                'trails' => $instance->auditTrails()->orderByDesc('id')->get(),
            ]));
    }

    /**
     * Champs de saisie déclarés pour les transitions qui exigent des données
     * dans le payload (ergonomie d'écran uniquement — le moteur reste maître
     * des gardes). Tout autre besoin de champ est à noter dans l'inventaire
     * de la fenêtre de schéma Phase 2.
     */
    protected function champsDeTransition(string $code): array
    {
        return match ($code) {
            'valider_recu' => [
                Toggle::make('reception_paiement')
                    ->label('Paiement vérifié sur inscription.tn')
                    ->default(false),
            ],
            default => [],
        };
    }

    protected function engine(): WorkflowEngine
    {
        return app(WorkflowEngine::class);
    }

    protected function peutPiloter(): bool
    {
        return auth()->user()?->can('update', $this->record) ?? false;
    }

    protected function rafraichirNotification(string $message, bool $danger = false): void
    {
        $notification = $danger
            ? Notification::make()->danger($message)
            : Notification::make()->success($message);

        $notification->send();

        $this->record->refresh();
        $this->fillForm();
    }

    protected function niveauParDefaut(): int
    {
        return match (trim((string) $this->record->annee_inscription)) {
            '1ere', '1ère' => 1,
            '2eme', '2ème' => 2,
            '3eme', '3ème' => 3,
            '4eme', '4ème' => 4,
            '5eme', '5ème' => 5,
            default => 2,
        };
    }
}