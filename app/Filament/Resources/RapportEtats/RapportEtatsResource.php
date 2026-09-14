<?php

namespace App\Filament\Resources\RapportEtats;

use App\Filament\Resources\RapportEtats\Pages\CreateRapportEtat;
use App\Filament\Resources\RapportEtats\Pages\EditRapportEtat;
use App\Filament\Resources\RapportEtats\Pages\ListRapportEtats;
use App\Filament\Resources\RapportEtats\Schemas\RapportEtatForm;
use App\Filament\Resources\RapportEtats\Tables\RapportEtatsTable;
use App\Models\RapportEtat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RapportEtatsResource extends Resource
{
    protected static ?string $model = RapportEtat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static string|\UnitEnum|null $navigationGroup = 'Archivage & audit';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return RapportEtatForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RapportEtatsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRapportEtats::route('/'),
            'create' => CreateRapportEtat::route('/create'),
            'edit' => EditRapportEtat::route('/{record}/edit'),
        ];
    }
}
