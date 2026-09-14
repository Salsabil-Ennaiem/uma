<?php

namespace App\Filament\Resources\Reunions\Pages;

use App\Enums\PresenceStatut;
use App\Filament\Resources\Reunions\ReunionResource;
use App\Models\Presence;
use App\Models\Reunion;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;

class ManageReunionPresences extends Page implements HasForms
{
    use InteractsWithForms;
    use InteractsWithRecord;

    protected static string $resource = ReunionResource::class;

    protected string $view = 'filament.resources.reunions.pages.manage-reunion-presences';

    protected static ?string $title = 'Enregistrement des présences';

    public ?array $formData = [];

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        abort_unless(auth()->user()?->can('enregistrerPresence', $this->record), 403);

        $this->loadForm();
    }

    public function loadForm(): void
    {
        /** @var Reunion $reunion */
        $reunion = $this->record;
        $invites = $reunion->invitations()->whereNotNull('participant_id')->get();
        $existingPresences = $reunion->presences()->get()->keyBy('participant_id');

        $items = [];
        foreach ($invites as $invitation) {
            $pid = $invitation->participant_id;
            $presence = $existingPresences->get($pid);
            $items[$pid] = [
                'participant_id' => $pid,
                'statut' => $presence?->statut?->value ?? 'absent',
                'note' => $presence?->note ?? '',
            ];
        }

        $this->form->fill(['presences' => $items]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Repeater::make('presences')
                    ->schema([
                        Select::make('participant_id')
                            ->label('Participant')
                            ->disabled()
                            ->options(fn () => User::pluck('name', 'id')),
                        Select::make('statut')
                            ->label('Présence')
                            ->options(collect(PresenceStatut::cases())->mapWithKeys(
                                fn (PresenceStatut $s) => [$s->value => $s->label()],
                            )->all())
                            ->default('absent')
                            ->required(),
                        Textarea::make('note')
                            ->label('Note')
                            ->rows(1),
                    ])
                    ->columns(3)
                    ->live()
                    ->itemLabel(fn (array $state): ?string => User::find($state['participant_id'])?->name),
            ]);
    }

    public function save(): void
    {
        /** @var Reunion $reunion */
        $reunion = $this->record;
        $data = $this->form->getState();

        foreach ($data['presences'] ?? [] as $row) {
            if (empty($row['participant_id'])) {
                continue;
            }

            Presence::updateOrCreate(
                [
                    'reunion_id' => $reunion->getKey(),
                    'participant_id' => $row['participant_id'],
                ],
                [
                    'statut' => $row['statut'] ?? 'absent',
                    'note' => $row['note'] ?? null,
                    'recorded_by' => auth()->id(),
                ],
            );
        }

        Notification::make()->success('Présences enregistrées avec succès.')->send();
    }
}
