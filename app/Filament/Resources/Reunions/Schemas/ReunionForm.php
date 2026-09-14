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
                DateTimePicker::make('date_debut')
                    ->label('Date et heure de début')
                    ->required(),
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
}
