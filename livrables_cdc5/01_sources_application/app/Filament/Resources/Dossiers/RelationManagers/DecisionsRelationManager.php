<?php

namespace App\Filament\Resources\Dossiers\RelationManagers;

use App\Models\Decision;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * Décisions du dossier visibles par année d'inscription (mallette, CDC §décisions).
 */
class DecisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'decisions';

    protected static ?string $title = 'Décisions par année d\'inscription';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')
                    ->label('Décision')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('template.label')
                    ->label('Modèle')
                    ->toggleable(),
                TextColumn::make('annee_inscription')
                    ->label('Année d\'inscription')
                    ->badge()
                    ->searchable(),
                TextColumn::make('decideur.name')
                    ->label('Décidée par')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('annee_inscription')
                    ->label('Année d\'inscription')
                    ->options($this->anneesOptions()),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }

    protected function anneesOptions(): array
    {
        return Decision::query()
            ->distinct()
            ->orderBy('annee_inscription')
            ->pluck('annee_inscription', 'annee_inscription')
            ->all();
    }
}
