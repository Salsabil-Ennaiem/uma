<?php

namespace App\Filament\Resources\EcoleDoctorales;

use App\Filament\Resources\EcoleDoctorales\Pages\ManageEcoleDoctorales;
use App\Models\EcoleDoctorale;
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

class EcoleDoctoraleResource extends Resource
{
    protected static ?string $model = EcoleDoctorale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|\UnitEnum|null $navigationGroup = 'Institution';

    protected static ?string $navigationLabel = 'Écoles doctorales';

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('universite_id')
                    ->label('Université')
                    ->relationship('universite', 'nom')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('nom')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
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
                TextColumn::make('universite.nom')
                    ->label('Université')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('etablissements_count')
                    ->label('Établissements')
                    ->counts('etablissements')
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
            'index' => ManageEcoleDoctorales::route('/'),
        ];
    }
}
