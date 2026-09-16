<?php

namespace App\Filament\Resources\Etablissements\Pages;

use App\Filament\Resources\Etablissements\EtablissementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEtablissements extends ManageRecords
{
    protected static string $resource = EtablissementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
