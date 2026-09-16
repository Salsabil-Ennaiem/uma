<?php

namespace App\Filament\Resources\Commissions;

use App\Filament\Resources\Commissions\Pages\ManageCommissions;
use App\Models\Commission;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CommissionResource extends Resource
{
    protected static ?string $model = Commission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static string|\UnitEnum|null $navigationGroup = 'Institution';

    protected static ?string $navigationLabel = 'Commissions';

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                TextInput::make('discipline')
                    ->label('Discipline')
                    ->maxLength(190),
                Select::make('etablissement_id')
                    ->label('Établissement')
                    ->relationship('etablissement', 'nom')
                    ->searchable()
                    ->preload(),
                Select::make('president_id')
                    ->label('Président')
                    ->relationship('president', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('membres')
                    ->label('Membres')
                    ->relationship('membres', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
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
                TextColumn::make('discipline')
                    ->label('Discipline')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('etablissement.nom')
                    ->label('Établissement')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('president.name')
                    ->label('Président')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('membres_count')
                    ->label('Membres')
                    ->counts('membres')
                    ->badge(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active'),
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
            'index' => ManageCommissions::route('/'),
        ];
    }
}
