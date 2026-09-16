<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Pages\Imports\ImportEnseignants;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importEnseignants')
                ->label('Importer enseignants')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->url(fn () => ImportEnseignants::getUrl())
                ->visible(fn () => auth()->user()?->can('create', \App\Models\User::class) ?? false),
        ];
    }
}