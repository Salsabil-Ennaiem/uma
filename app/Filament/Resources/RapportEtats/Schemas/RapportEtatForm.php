<?php

namespace App\Filament\Resources\RapportEtats\Schemas;

use App\Enums\RapportEtatType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RapportEtatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->label('Libellé de l\'état')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->label('Nature')
                    ->options(collect(RapportEtatType::cases())->mapWithKeys(
                        fn (RapportEtatType $t) => [$t->value => $t->label()],
                    )->all())
                    ->default(RapportEtatType::Etat->value)
                    ->required(),
                Select::make('pv_type')
                    ->label('Type de rendu (moteur du package)')
                    ->options(collect(config('pv-module.types', ['pv']))
                        ->push('rapport', 'etat')
                        ->filter()
                        ->mapWithKeys(fn (string $t) => [$t => $t])
                        ->all())
                    ->helperText('Sélectionne le PvTemplate actif utilisé par le package (PvTemplate + PdfService).')
                    ->default('pv')
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                Textarea::make('en_tete')
                    ->label('En-tête (marge haute des pages)')
                    ->helperText('Peut contenir le logo, la mention officielle, etc. Rendu via mPDF.')
                    ->rows(3)
                    ->columnSpanFull(),
                Select::make('orientation')
                    ->label('Orientation')
                    ->options([
                        'portrait' => 'Portrait',
                        'landscape' => 'Paysage',
                    ])
                    ->default('portrait')
                    ->required(),
                Select::make('format_papier')
                    ->label('Format papier')
                    ->options(['A4' => 'A4'])
                    ->helperText('Seul A4 est livré avec le moteur ; tout autre format implique une extension du package (sous-prompt P4).')
                    ->default('A4'),
                TextInput::make('marges.top')
                    ->label('Marge haute (mm)')
                    ->numeric()
                    ->default(20),
                TextInput::make('marges.bottom')
                    ->label('Marge basse (mm)')
                    ->numeric()
                    ->default(20),
                TextInput::make('marges.left')
                    ->label('Marge gauche (mm)')
                    ->numeric()
                    ->default(20),
                TextInput::make('marges.right')
                    ->label('Marge droite (mm)')
                    ->numeric()
                    ->default(20),
                Toggle::make('is_active')
                    ->label('État actif')
                    ->default(true),
            ]);
    }
}
