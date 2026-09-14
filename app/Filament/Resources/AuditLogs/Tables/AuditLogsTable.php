<?php

namespace App\Filament\Resources\AuditLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('action')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Quand')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Qui')
                    ->searchable(),
                TextColumn::make('action')
                    ->label('Quoi (action)')
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('entity_type')
                    ->label('Entité')
                    ->formatStateUsing(function ($state) {
                        if ($state === null) {
                            return '—';
                        }

                        return str($state)->afterLast('\\')->headline()->toString();
                    })
                    ->toggleable(),
                TextColumn::make('entity_id')
                    ->label('ID')
                    ->toggleable()
                    ->color('gray'),
                TextColumn::make('before')
                    ->label('Avant')
                    ->formatStateUsing(function ($state) {
                        return $state ? json_encode($state, JSON_PRETTY_PRINT) : '—';
                    })
                    ->toggleable(),
                TextColumn::make('after')
                    ->label('Après')
                    ->formatStateUsing(function ($state) {
                        return $state ? json_encode($state, JSON_PRETTY_PRINT) : '—';
                    })
                    ->toggleable(),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable()
                    ->color('gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([25, 50, 100]);
    }
}
