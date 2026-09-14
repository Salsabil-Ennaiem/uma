<?php

namespace App\Filament\Resources\EcoleDoctorales\Pages;

use App\Filament\Resources\EcoleDoctorales\EcoleDoctoraleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEcoleDoctorales extends ManageRecords
{
    protected static string $resource = EcoleDoctoraleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
