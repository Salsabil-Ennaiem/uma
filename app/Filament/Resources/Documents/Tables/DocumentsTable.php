<?php

namespace App\Filament\Resources\Documents\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label('Libellé')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('documentable_type')
                    ->label('Rattaché à')
                    ->formatStateUsing(fn (?string $state) => str($state)->afterLast('\\')->headline()->toString())
                    ->badge(),
                TextColumn::make('versions_count')
                    ->label('Versions')
                    ->counts('versions'),
                TextColumn::make('retention_until')
                    ->label('Rétention jusqu\'au')
                    ->date('d/m/Y'),
                TextColumn::make('creator.name')
                    ->label('Archivé par')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Archivé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
