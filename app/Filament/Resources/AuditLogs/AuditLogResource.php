<?php

namespace App\Filament\Resources\AuditLogs;

use App\Enums\UserRole;
use App\Filament\Resources\AuditLogs\Pages\ListAuditLogs;
use App\Filament\Resources\AuditLogs\Tables\AuditLogsTable;
use App\Models\AuditLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * Journal d'audit (append-only) : consultation seule, réservée à l'administrateur.
 */
class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFingerPrint;

    protected static string|\UnitEnum|null $navigationGroup = 'Archivage & audit';

    protected static ?string $recordTitleAttribute = 'action';

    public static function canAccess(): bool
    {
        return auth()->user()?->role === UserRole::Admin;
    }

    public static function table(Table $table): Table
    {
        return AuditLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuditLogs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
