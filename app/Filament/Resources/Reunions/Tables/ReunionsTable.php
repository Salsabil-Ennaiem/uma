<?php

namespace App\Filament\Resources\Reunions\Tables;

use App\Enums\ReunionStatut;
use App\Enums\ReunionType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ReunionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('commission.nom')
                    ->label('Commission')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('objet')
                    ->label('Objet')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('date_debut')
                    ->label('Début')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('date_fin')
                    ->label('Fin')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('lieu')
                    ->label('Lieu')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (ReunionType $state) => $state->label()),
                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (ReunionStatut $state) => $state->label())
                    ->color(fn (ReunionStatut $state) => match ($state) {
                        ReunionStatut::Brouillon => 'gray',
                        ReunionStatut::Planifiee => 'info',
                        ReunionStatut::EnCours => 'success',
                        ReunionStatut::Terminee => 'primary',
                        ReunionStatut::Annulee => 'danger',
                    }),
                TextColumn::make('invitations_count')
                    ->label('Invités')
                    ->counts('invitations'),
                TextColumn::make('decisions_count')
                    ->label('Décisions')
                    ->counts('decisions'),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Supprimé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->options(collect(ReunionStatut::cases())->mapWithKeys(
                        fn (ReunionStatut $s) => [$s->value => $s->label()],
                    )->all()),
                SelectFilter::make('commission_id')
                    ->label('Commission')
                    ->relationship('commission', 'nom')
                    ->searchable(),
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
