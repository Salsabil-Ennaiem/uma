<?php

namespace App\Filament\Resources\Etablissements;

use App\Filament\Resources\Etablissements\Pages\ManageEtablissements;
use App\Models\Etablissement;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EtablissementResource extends Resource
{
    protected static ?string $model = Etablissement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|\UnitEnum|null $navigationGroup = 'Institution';

    protected static ?string $navigationLabel = 'Établissements';

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Select::make('ecole_doctorale_id')
                    ->label('École doctorale')
                    ->relationship('ecoleDoctorale', 'nom')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('directeur_id')
                    ->label('Directeur / Doyen')
                    ->relationship('directeur', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
                TextColumn::make('nom')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('ecoleDoctorale.nom')
                    ->label('École doctorale')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('directeur.name')
                    ->label('Directeur')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('commissions_count')
                    ->label('Commissions')
                    ->counts('commissions')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEtablissements::route('/'),
        ];
    }
}
