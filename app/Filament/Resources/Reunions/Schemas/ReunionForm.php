<?php

namespace App\Filament\Resources\Reunions\Schemas;

use App\Enums\DossierStatut;
use App\Enums\ReunionStatut;
use App\Enums\ReunionType;
use App\Filament\Resources\Reunions\Pages\CreateReunion;
use App\Models\Dossier;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use WooServ\FilamentDateTimeSlots\Forms\Components\DateTimeSlotPicker;

class ReunionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('commission_id')
                    ->label('Commission (discipline)')
                    ->relationship('commission', 'nom')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Select $component, $state, Schema $live) {
                        $live->getComponent('dossiers')?->options(
                            Dossier::query()
                                ->where('commission_id', $state)
                                ->where('statut', DossierStatut::EnAttente->value)
                                ->pluck('objet', 'id'),
                        );
                    })
                    ->helperText('Les membres de la commission seront ajoutés automatiquement à la création.'),
                Select::make('dossiers')
                    ->label('Dossiers à l\'ordre du jour')
                    ->relationship('dossiers', 'objet')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(fn () => Dossier::query()
                        ->where('statut', DossierStatut::EnAttente->value)
                        ->pluck('objet', 'id'))
                    ->helperText('Demandes en attente de décision (auto-alimentées par l\'administratif).')
                    ->columnSpanFull(),
                TextInput::make('objet')
                    ->label('Objet')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                Select::make('odj_template_id')
                    ->label('Modèle d’ordre du jour')
                    ->relationship('odjTemplate', 'label')
                    ->searchable()
                    ->preload(),
                Textarea::make('ordre_du_jour')
                    ->label('Ordre du jour')
                    ->columnSpanFull()
                    ->rows(8),
                DateTimeSlotPicker::make('date_debut')
                    ->label('Date puis creneau (heures ouvrees)')
                    ->format('Y-m-d H:i')
                    ->minDate(now())
                    ->minimumLeadTime(30)
                    ->slotInterval(30)
                    ->workingHours([
                        'monday' => ['08:00', '18:00'],
                        'tuesday' => ['08:00', '18:00'],
                        'wednesday' => ['08:00', '18:00'],
                        'thursday' => ['08:00', '18:00'],
                        'friday' => ['08:00', '18:00'],
                        'saturday' => ['09:00', '12:00'],
                    ])
                    ->blockedSlots(fn () => self::blockedByCommission())
                    ->showBlockedSlots()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            try {
                                $set('date_fin', \Carbon\Carbon::parse($state)->addHours(2)->format('Y-m-d H:i:s'));
                            } catch (\Throwable) {
                            }
                        }
                    }),
                DateTimePicker::make('date_fin')
                    ->label('Date et heure de fin')
                    ->after('date_debut'),
                TextInput::make('lieu')
                    ->label('Lieu')
                    ->maxLength(255),
                TextInput::make('lien')
                    ->label('Lien visio')
                    ->url()
                    ->maxLength(255),
                Select::make('type')
                    ->label('Type')
                    ->options(ReunionType::class)
                    ->default(ReunionType::Presentiel->value)
                    ->required(),
                Select::make('statut')
                    ->label('Statut')
                    ->options(ReunionStatut::class)
                    ->default(ReunionStatut::Brouillon->value)
                    ->required()
                    ->disabled(fn ($livewire) => $livewire instanceof CreateReunion),
            ]);
    }

    /** Creneaux deja pris par commission : ['Y-m-d' => ['H:i', ...]]. */
    protected static function blockedByCommission(): array
    {
        $out = [];
        $rows = \App\Models\Reunion::query()
            ->select(['commission_id', 'date_debut'])
            ->whereNotNull('date_debut')
            ->where('date_debut', '>=', now()->startOfDay())
            ->limit(500)
            ->get();
        foreach ($rows as $r) {
            $d = $r->date_debut instanceof \DateTimeInterface ? $r->date_debut->format('Y-m-d') : substr((string) $r->date_debut, 0, 10);
            $h = $r->date_debut instanceof \DateTimeInterface ? $r->date_debut->format('H:i') : substr((string) $r->date_debut, 11, 5);
            $out[$d][] = $h;
        }

        return $out;
    }
}
