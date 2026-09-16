<?php

namespace App\Filament\Resources\Documents\Pages;

use App\Filament\Resources\Documents\DocumentResource;
use App\Models\Decision;
use App\Models\Document;
use App\Models\Dossier;
use App\Models\Reunion;
use App\Models\User;
use App\Services\ArchiveService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function handleRecordCreation(array $data): Document
    {
        $fichier = $data['fichier'] ?? null;
        unset($data['fichier']);

        if ($fichier !== null && is_string($fichier)) {
            $fichier = Storage::disk(config('archive.disk', 'public'))->path($fichier);
        }

        if ($fichier !== null && ! is_object($fichier)) {
            $fichier = new UploadedFile($fichier, basename($fichier), null, null, true);
        }

        $documentable = $this->resoudreDocumentable($data['documentable_type'], $data['documentable_id']);

        /** @var ArchiveService $archives */
        $archives = app(ArchiveService::class);

        return $archives->archiverUpload(
            $documentable,
            $data['type'],
            $data['label'],
            $fichier,
            [],
            $data['retention_months'] ?? null,
            $data['description'] ?? null,
            auth()->user(),
        );
    }

    protected function resoudreDocumentable(string $type, int $id): mixed
    {
        $model = match ($type) {
            Dossier::class => Dossier::class,
            Reunion::class => Reunion::class,
            Decision::class => Decision::class,
            default => User::class,
        };

        return $model::findOrFail($id);
    }
}
