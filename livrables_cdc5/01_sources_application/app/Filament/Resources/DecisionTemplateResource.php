<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DecisionTemplateResource\Pages;
use App\Models\DecisionTemplate;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class DecisionTemplateResource extends Resource
{
    protected static ?string $model = DecisionTemplate::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Modèles de décision';

    protected static ?string $modelLabel = 'modèle de décision';

    protected static ?string $pluralModelLabel = 'modèles de décision';

    protected static string|\UnitEnum|null $navigationGroup = 'Décisions';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('Label')
                    ->required()
                    ->maxLength(190),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(2),
                Forms\Components\TextInput::make('email_subject')
                    ->label('Objet de l’email')
                    ->required()
                    ->maxLength(190)
                    ->helperText('Sujet du mail envoyé au doctorant lors de la décision.'),
                Forms\Components\Textarea::make('email_body')
                    ->label('Contenu de l’email')
                    ->required()
                    ->rows(8)
                    ->helperText('Corps du mail (gabarit réutilisable, variables : {prenom}, {nom}, {label}).'),
                Forms\Components\TextInput::make('commission_id')
                    ->label('Commission')
                    ->integer()
                    ->placeholder('Toutes les commissions'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_subject')
                    ->label('Objet de l’email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDecisionTemplates::route('/'),
            'create' => Pages\CreateDecisionTemplate::route('/create'),
            'edit' => Pages\EditDecisionTemplate::route('/{record}/edit'),
        ];
    }
}
