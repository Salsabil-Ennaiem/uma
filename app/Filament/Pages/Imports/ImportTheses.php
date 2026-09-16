<?php

namespace App\Filament\Pages\Imports;

use App\Imports\TheseImport;
use App\Models\Dossier;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class ImportTheses extends Page
{
    use WithFileUploads;

    protected string $view = 'filament.pages.imports.importer';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static string|\UnitEnum|null $navigationGroup = 'Doctorat';

    protected static ?string $navigationLabel = 'Import thèses en cours';

    protected static ?int $navigationSort = 30;

    /** @var TemporaryUploadedFile|string|null */
    public $file = null;

    public ?int $committed = null;

    /** @var array<int, array{row: int, data: array<string, mixed>, errors: array<int, string>}> */
    public array $preview = [];

    public ?string $sourcePath = null;

    public ?string $sourceExtension = null;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('importTheses', Dossier::class) ?? false;
    }

    protected function importer(): TheseImport
    {
        return new TheseImport;
    }

    public function analyze(): void
    {
        $this->validate([
            'file' => ['required', 'file'],
        ]);

        if (! $this->file instanceof TemporaryUploadedFile) {
            return;
        }

        $ext = $this->file->getClientOriginalExtension();
        $stored = storage_path('app/private/tmp-imports/'.uniqid('imp_', true).'.'.$ext);
        @mkdir(dirname($stored), 0755, true);
        copy($this->file->getRealPath(), $stored);
        $this->sourcePath = $stored;
        $this->sourceExtension = $ext;

        $this->refreshPreview();
    }

    public function refreshPreview(): void
    {
        if ($this->sourcePath === null) {
            return;
        }

        $result = $this->importer()->analyze($this->sourcePath, (string) $this->sourceExtension);

        $this->preview = array_map(
            fn ($row) => [
                'row' => $row->rowNumber,
                'data' => $row->data,
                'errors' => $row->errors,
            ],
            $result->rows,
        );
    }

    public function commit(): void
    {
        if ($this->sourcePath === null) {
            return;
        }

        $importer = $this->importer();
        $result = $importer->commit($this->sourcePath, (string) $this->sourceExtension);
        $this->committed = $result->committed;

        $this->refreshPreview();

        Notification::make()
            ->title(sprintf('Import %s terminé', $importer->label()))
            ->body(sprintf('%d ligne(s) intégrée(s), %d en erreur.', $result->committed, $result->countInvalid()))
            ->success()
            ->send();
    }

    public function downloadErrors()
    {
        if ($this->sourcePath === null) {
            return null;
        }

        $result = $this->importer()->analyze($this->sourcePath, (string) $this->sourceExtension);

        return response()->streamDownload(
            fn () => print $this->importer()->errorReportCsv($result),
            'rapport-erreurs-moulinet.csv',
            ['Content-Type' => 'text/csv; charset=UTF-8'],
        );
    }
}