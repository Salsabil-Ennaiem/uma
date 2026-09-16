<?php

namespace App\Filament\Resources\Reunions;

use App\Filament\Resources\Reunions\Pages\CreateReunion;
use App\Filament\Resources\Reunions\Pages\EditReunion;
use App\Filament\Resources\Reunions\Pages\ListReunions;
use App\Filament\Resources\Reunions\Pages\ManageReunionDecisions;
use App\Filament\Resources\Reunions\Pages\ManageReunionPresences;
use App\Filament\Resources\Reunions\Pages\ReunionCorbeille;
use App\Filament\Resources\Reunions\Schemas\ReunionForm;
use App\Filament\Resources\Reunions\Tables\ReunionsTable;
use App\Models\Reunion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReunionResource extends Resource
{
    protected static ?string $model = Reunion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|\UnitEnum|null $navigationGroup = 'Réunions';

    protected static ?string $recordTitleAttribute = 'objet';

    public static function form(Schema $schema): Schema
    {
        return ReunionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReunionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReunions::route('/'),
            'create' => CreateReunion::route('/create'),
            'edit' => EditReunion::route('/{record}/edit'),
            'presences' => ManageReunionPresences::route('/{record}/presences'),
            'decisions' => ManageReunionDecisions::route('/{record}/decisions'),
            'corbeille' => ReunionCorbeille::route('/corbeille'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
