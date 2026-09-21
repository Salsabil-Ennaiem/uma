<?php

namespace App\Filament\Resources\Reunions\Pages;

use App\Filament\Resources\Reunions\ReunionResource;
use App\Services\ReunionService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReunion extends CreateRecord
{
    protected static string $resource = ReunionResource::class;

    public function mount(): void
    {
        parent::mount();

        // Pré-remplit le créneau depuis le calendrier (?jour=YYYY-MM-DD).
        $jour = request()->query('jour');
        if (is_string($jour) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $jour)) {
            $this->form->fill(['date_debut' => $jour.' 09:00']);
        }
    }

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
