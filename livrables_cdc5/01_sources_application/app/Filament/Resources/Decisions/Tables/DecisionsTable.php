<?php

namespace App\Filament\Resources\Decisions\Tables;

use App\Models\Decision;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DecisionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reunion.objet')
                    ->label('Réunion')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('dossier.doctorant.name')
                    ->label('Doctorant')
                    ->searchable(),
                TextColumn::make('dossier.objet')
                    ->label('Dossier')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),
                TextColumn::make('label')
                    ->label('Décision')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('annee_inscription')
                    ->label('Année')
                    ->searchable()
                    ->badge(),
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
                    ->options(fn () => Decision::query()
                        ->distinct()
                        ->orderBy('annee_inscription')
                        ->pluck('annee_inscription', 'annee_inscription')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
