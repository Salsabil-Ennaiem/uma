<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

/**
 * Exporter Filament natif pour la liste des utilisateurs.
 * Couvre §6 : export du résultat filtré en CSV (ou XLSX).
 */
class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')->label('Nom'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('role')->label('Rôle'),
            ExportColumn::make('commissions_count')->label('Nombre de commissions'),
            ExportColumn::make('created_at')->label('Créé le'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'L\'export des utilisateurs est terminé ('.$export->getAttribute('total_rows').' lignes).';
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->withCount('commissions');
    }
}