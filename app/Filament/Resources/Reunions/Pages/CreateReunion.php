<?php

namespace App\Filament\Resources\Reunions\Pages;

use App\Filament\Resources\Reunions\ReunionResource;
use App\Services\ReunionService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReunion extends CreateRecord
{
    protected static string $resource = ReunionResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $reunion = app(ReunionService::class)->create($data, auth()->user());

        $this->record = $reunion;

        return $reunion;
    }

    protected function getRedirectUrl(): string
    {
        return ReunionResource::getUrl('edit', ['record' => $this->record]);
    }
}
