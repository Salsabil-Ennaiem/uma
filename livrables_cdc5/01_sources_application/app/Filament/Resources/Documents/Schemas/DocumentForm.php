<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Models\Decision;
use App\Models\Dossier;
use App\Models\Reunion;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('documentable_type')
                    ->label('Rattaché à (type)')
                    ->options([
                        Dossier::class => 'Dossier / doctorant (mallette)',
                        Reunion::class => 'Réunion',
                        Decision::class => 'Décision',
                        User::class => 'Compte (doctorant)',
                    ])
                    ->required()
                    ->live(),
                TextInput::make('documentable_id')
                    ->label('Rattaché à (ID)')
                    ->numeric()
                    ->required(),
                Select::make('type')
                    ->label('Type de pièce')
                    ->options(collect(config('archive.types', []))
                        ->mapWithKeys(fn (string $t) => [$t => Str::headline($t)])
                        ->all())
                    ->required(),
                TextInput::make('label')
                    ->label('Libellé')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                TextInput::make('retention_months')
                    ->label('Rétention (mois) — laissez vide pour la valeur par défaut')
                    ->numeric()
                    ->minValue(1),
                FileUpload::make('fichier')
                    ->label('Pièce à archiver')
                    ->disk(config('archive.disk', 'public'))
                    ->directory('uploads/tmp')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
