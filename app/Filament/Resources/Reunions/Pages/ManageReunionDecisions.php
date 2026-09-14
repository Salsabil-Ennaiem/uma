<?php

namespace App\Filament\Resources\Reunions\Pages;

use App\Filament\Resources\Reunions\ReunionResource;
use App\Models\Decision;
use App\Models\DecisionTemplate;
use App\Models\Dossier;
use App\Models\Reunion;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;

class ManageReunionDecisions extends Page implements HasForms
{
    use InteractsWithForms;
    use InteractsWithRecord;

    protected static string $resource = ReunionResource::class;

    protected string $view = 'filament.resources.reunions.pages.manage-reunion-decisions';

    protected static ?string $title = 'Décisions de réunion';

    public ?array $formData = [];

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        abort_unless(auth()->user()?->can('gererDecisions', $this->record), 403);

        $this->loadForm();
    }

    public function loadForm(): void
    {
        /** @var Reunion $reunion */
        $reunion = $this->record;
        $existingDecisions = $reunion->decisions()->get()->keyBy('dossier_id');

        $items = [];
        foreach ($reunion->dossiers as $dossier) {
            $decision = $existingDecisions->get($dossier->getKey());
            $items[] = [
                'dossier_id' => $dossier->getKey(),
                'decision_template_id' => $decision?->decision_template_id ?? null,
                'annee_inscription' => $decision?->annee_inscription ?? $dossier->annee_inscription ?? '',
            ];
        }

        if (empty($items)) {
            $items[] = [
                'dossier_id' => null,
                'decision_template_id' => null,
                'annee_inscription' => '',
            ];
        }

        $this->form->fill(['decisions' => $items]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Repeater::make('decisions')
                    ->schema([
                        Select::make('dossier_id')
                            ->label('Dossier')
                            ->options(fn () => Dossier::pluck('objet', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('decision_template_id')
                            ->label('Modèle de décision')
                            ->options(fn () => DecisionTemplate::where('is_active', true)->pluck('label', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (! $state) {
                                    return;
                                }
                                $tpl = DecisionTemplate::find($state);
                                if ($tpl) {
                                    $set('label_snapshot', $tpl->label);
                                    $set('email_subject', $tpl->email_subject);
                                }
                            }),
                        TextInput::make('label_snapshot')
                            ->label('Libellé')
                            ->disabled(),
                        TextInput::make('email_subject')
                            ->label('Objet email')
                            ->disabled(),
                        TextInput::make('annee_inscription')
                            ->label('Année d\'inscription'),
                    ])
                    ->columns(2),
            ]);
    }

    public function save(): void
    {
        /** @var Reunion $reunion */
        $reunion = $this->record;
        $data = $this->form->getState();

        foreach ($data['decisions'] ?? [] as $row) {
            if (empty($row['dossier_id']) || empty($row['decision_template_id'])) {
                continue;
            }

            $template = DecisionTemplate::find($row['decision_template_id']);

            if (! $template) {
                continue;
            }

            Decision::updateOrCreate(
                [
                    'reunion_id' => $reunion->getKey(),
                    'dossier_id' => $row['dossier_id'],
                ],
                [
                    'decision_template_id' => $template->getKey(),
                    'label' => $template->label,
                    'email_subject' => $template->email_subject,
                    'email_body' => $template->email_body,
                    'annee_inscription' => $row['annee_inscription'] ?? $template->commission?->discipline,
                    'decided_by' => auth()->id(),
                ],
            );

            Dossier::whereKey($row['dossier_id'])->update(['statut' => 'traite']);
        }

        Notification::make()->success('Décisions enregistrées avec succès.')->send();
    }
}
