<?php

namespace App\Filament\Resources\Dossiers\Pages;

use App\Filament\Pages\Imports\ImportTheses;
use App\Filament\Resources\Dossiers\DossierResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDossiers extends ListRecords
{
    protected static string $resource = DossierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importTheses')
                ->label('Importer thèses (moulinet)')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->url(fn () => ImportTheses::getUrl())
                ->visible(fn () => auth()->user()?->can('importTheses', \App\Models\Dossier::class) ?? false),
            CreateAction::make(),
        ];
    }
}
