<?php

namespace App\Filament\Resources\RapportEtats\Pages;

use App\Filament\Resources\RapportEtats\RapportEtatsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRapportEtats extends ListRecords
{
    protected static string $resource = RapportEtatsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
