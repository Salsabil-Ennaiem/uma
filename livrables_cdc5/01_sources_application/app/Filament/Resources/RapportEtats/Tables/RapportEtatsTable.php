<?php

namespace App\Filament\Resources\RapportEtats\Tables;

use App\Enums\RapportEtatType;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RapportEtatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label('État / rapport')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('type')
                    ->label('Nature')
                    ->badge()
                    ->formatStateUsing(fn (RapportEtatType $state) => $state->label()),
                TextColumn::make('pv_type')
                    ->label('Type de rendu')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('orientation')
                    ->label('Orientation')
                    ->badge()
                    ->color('gray'),
                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
                TextColumn::make('creator.name')
                    ->label('Créé par')
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Mis à jour le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
