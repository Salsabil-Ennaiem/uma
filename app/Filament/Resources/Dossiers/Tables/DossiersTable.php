<?php

namespace App\Filament\Resources\Dossiers\Tables;

use App\Enums\DossierStatut;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DossiersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('doctorant.name')
                    ->label('Doctorant')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('commission.nom')
                    ->label('Commission')
                    ->searchable(),
                TextColumn::make('objet')
                    ->label('Objet')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (DossierStatut $state) => $state->label())
                    ->color(fn (DossierStatut $state) => match ($state) {
                        DossierStatut::EnAttente => 'warning',
                        DossierStatut::EnCours => 'info',
                        DossierStatut::Traite => 'success',
                    }),
                TextColumn::make('annee_inscription')
                    ->label('Année')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('decisions_count')
                    ->label('Décisions')
                    ->counts('decisions'),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->options(collect(DossierStatut::cases())->mapWithKeys(
                        fn (DossierStatut $s) => [$s->value => $s->label()],
                    )->all()),
                SelectFilter::make('commission_id')
                    ->label('Commission')
                    ->relationship('commission', 'nom'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
