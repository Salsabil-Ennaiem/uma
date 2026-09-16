<?php

namespace App\Filament\Resources\Universites\Pages;

use App\Filament\Resources\Universites\UniversiteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUniversites extends ManageRecords
{
    protected static string $resource = UniversiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
