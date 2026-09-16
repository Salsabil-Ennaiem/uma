<?php

namespace App\Filament\Resources\Decisions;

use App\Filament\Resources\Decisions\Pages\CreateDecision;
use App\Filament\Resources\Decisions\Pages\EditDecision;
use App\Filament\Resources\Decisions\Pages\ListDecisions;
use App\Filament\Resources\Decisions\Schemas\DecisionForm;
use App\Filament\Resources\Decisions\Tables\DecisionsTable;
use App\Models\Decision;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DecisionResource extends Resource
{
    protected static ?string $model = Decision::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Réunions';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return DecisionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DecisionsTable::configure($table);
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
            'index' => ListDecisions::route('/'),
            'create' => CreateDecision::route('/create'),
            'edit' => EditDecision::route('/{record}/edit'),
        ];
    }
}
