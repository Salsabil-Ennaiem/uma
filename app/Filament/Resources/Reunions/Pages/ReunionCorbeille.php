<?php

namespace App\Filament\Resources\Reunions\Pages;

use App\Enums\ReunionStatut;
use App\Enums\UserRole;
use App\Filament\Resources\Reunions\ReunionResource;
use App\Models\Reunion;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReunionCorbeille extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = ReunionResource::class;

    protected string $view = 'filament.resources.reunions.pages.reunion-corbeille';

    protected static ?string $title = 'Corbeille des réunions';

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()?->role === UserRole::Admin;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Reunion::query()->onlyTrashed())
            ->recordTitleAttribute('objet')
            ->columns([
                TextColumn::make('commission.nom')
                    ->label('Commission'),
                TextColumn::make('objet')
                    ->label('Objet'),
                TextColumn::make('date_debut')
                    ->label('Début')
                    ->dateTime('d/m/Y H:i'),
                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (ReunionStatut $state) => $state->label()),
                TextColumn::make('deleted_at')
                    ->label('Supprimée le')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->recordActions([
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->paginated([10, 25, 50]);
    }
}
