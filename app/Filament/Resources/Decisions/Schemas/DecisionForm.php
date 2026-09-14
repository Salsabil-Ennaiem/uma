<?php

namespace App\Filament\Resources\Decisions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DecisionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('reunion_id')
                    ->label('Réunion')
                    ->relationship('reunion', 'objet')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('dossier_id')
                    ->label('Dossier (demande)')
                    ->relationship('dossier', 'objet')
                    ->searchable()
                    ->preload(),
                Select::make('decision_template_id')
                    ->label('Modèle de décision')
                    ->relationship('template', 'label')
                    ->searchable()
                    ->preload(),
                TextInput::make('label')
                    ->label('Libellé de la décision')
                    ->required(),
                TextInput::make('email_subject')
                    ->label('Objet de l\'email')
                    ->maxLength(255),
                Textarea::make('email_body')
                    ->label('Contenu de l\'email')
                    ->columnSpanFull(),
                TextInput::make('annee_inscription')
                    ->label('Année d\'inscription'),
            ]);
    }
}
