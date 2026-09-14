<?php

namespace App\Filament\Resources\Dossiers\RelationManagers;

use App\Models\Document;
use App\Services\ArchiveService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Mallette du dossier : arborescence des documents (types), versions, rétention.
 */
class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Pièces archivées (mallette)';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')
                    ->label('Libellé')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('versions_count')
                    ->label('Versions')
                    ->counts('versions')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('retention_until')
                    ->label('Rétention jusqu\'au')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->label('Archivé par')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Archivé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                Action::make('televerser')
                    ->label('Téléverser une pièce')
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->form([
                        Select::make('type')
                            ->label('Type de pièce')
                            ->options($this->typesOptions())
                            ->required(),
                        TextInput::make('label')
                            ->label('Libellé')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('fichier')
                            ->label('Fichier')
                            ->disk(config('archive.disk', 'public'))
                            ->directory('uploads/tmp')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        app(ArchiveService::class)->archiverUpload(
                            $this->getOwnerRecord(),
                            $data['type'],
                            $data['label'],
                            $this->toUploadedFile($data['fichier']),
                            [],
                            null,
                            null,
                            auth()->user(),
                        );

                        $this->dispatch('refresh');
                    }),
            ])
            ->recordActions([
                Action::make('telecharger')
                    ->label('Télécharger')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Document $record) {
                        $derniere = $record->lastVersion();

                        if ($derniere === null) {
                            return;
                        }

                        return Storage::disk(config('archive.disk', 'public'))->download($derniere->file_path, $derniere->file_name);
                    }),
                Action::make('nouvelle_version')
                    ->label('Nouvelle version')
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        FileUpload::make('fichier')
                            ->label('Fichier (nouvelle version)')
                            ->disk(config('archive.disk', 'public'))
                            ->directory('uploads/tmp')
                            ->required(),
                    ])
                    ->action(function (array $data, Document $record) {
                        $fichier = $this->toUploadedFile($data['fichier']);

                        app(ArchiveService::class)->nouvelleVersionContenu(
                            $record,
                            (string) file_get_contents($fichier->getPathname()),
                            $fichier->getClientOriginalName(),
                            $fichier->getMimeType(),
                            [],
                            auth()->user(),
                        );

                        $this->dispatch('refresh');
                    }),
            ])
            ->paginated([10, 25, 50]);
    }

    protected function typesOptions(): array
    {
        return collect(config('archive.types', []))
            ->mapWithKeys(fn (string $t) => [$t => str($t)->headline()->toString()])
            ->all();
    }

    protected function toUploadedFile(mixed $fichier): UploadedFile
    {
        if ($fichier instanceof UploadedFile) {
            return $fichier;
        }

        $tmp = tempnam(sys_get_temp_dir(), 'arch');
        file_put_contents($tmp, (string) Storage::disk(config('archive.disk', 'public'))->get($fichier));

        return new UploadedFile($tmp, basename($fichier), Storage::disk(config('archive.disk', 'public'))->mimeType($fichier), null, true);
    }
}
