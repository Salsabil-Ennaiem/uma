<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserRole;
use App\Filament\Actions\SendEmailBulkAction;
use App\Filament\Exports\UserExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('role')
                    ->label('Rôle')
                    ->badge()
                    ->formatStateUsing(fn (?UserRole $state) => $state?->label() ?? '—')
                    ->color(fn (?UserRole $state) => match ($state) {
                        UserRole::Admin => 'danger',
                        UserRole::PresidentCommission => 'warning',
                        UserRole::DirecteurThese => 'info',
                        UserRole::Doctorant => 'success',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('commissions_count')
                    ->label('Commissions')
                    ->counts('commissions')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Rôle')
                    ->options(collect(UserRole::cases())->mapWithKeys(
                        fn (UserRole $r) => [$r->value => $r->label()],
                    )->all()),
                SelectFilter::make('commission_id')
                    ->label('Commission')
                    ->relationship('commissions', 'nom'),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Exporter CSV')
                    ->exporter(UserExporter::class)
                    ->formats([ExportFormat::Csv]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    SendEmailBulkAction::make(),
                ]),
            ])
            ->recordActions([])
            ->paginated([10, 25, 50]);
    }
}