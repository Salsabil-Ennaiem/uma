<?php

namespace App\Filament\Resources\Dossiers\Schemas;

use App\Enums\DossierStatut;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DossierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('doctorant_id')
                    ->label('Doctorant')
                    ->relationship('doctorant', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('commission_id')
                    ->label('Commission')
                    ->relationship('commission', 'nom')
                    ->searchable()
                    ->preload(),
                TextInput::make('objet')
                    ->label('Objet de la demande')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                Select::make('statut')
                    ->label('Statut')
                    ->options(collect(DossierStatut::cases())->mapWithKeys(
                        fn (DossierStatut $s) => [$s->value => $s->label()],
                    )->all())
                    ->default(DossierStatut::EnAttente->value)
                    ->required(),
                TextInput::make('annee_inscription')
                    ->label('Année d\'inscription'),
            ]);
    }
}
