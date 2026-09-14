<?php

namespace App\Filament\Resources\Documents\RelationManagers;

use App\Models\DocumentVersion;
use App\Services\ArchiveService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class DocumentVersionsRelationManager extends RelationManager
{
    protected static string $relationship = 'versions';

    protected static ?string $title = 'Versions';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_name')
            ->columns([
                TextColumn::make('version')
                    ->label('Version')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('file_name')
                    ->label('Fichier')
                    ->limit(40),
                TextColumn::make('mime_type')
                    ->label('Type MIME')
                    ->toggleable()
                    ->color('gray'),
                TextColumn::make('size')
                    ->label('Taille')
                    ->formatStateUsing(fn ($state) => number_format((int) $state / 1024, 1).' Ko')
                    ->toggleable(),
                TextColumn::make('hash')
                    ->label('Hash SHA-256')
                    ->limit(16)
                    ->toggleable(),
                TextColumn::make('creator.name')
                    ->label('Par'),
                TextColumn::make('created_at')
                    ->label('Ajoutée le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('version', 'desc')
            ->toolbarActions([
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
                    ->action(function (array $data) {
                        $fichier = $data['fichier'];

                        $contenu = is_object($fichier)
                            ? (string) file_get_contents($fichier->getPathname())
                            : (string) Storage::disk(config('archive.disk', 'public'))->get($fichier);

                        app(ArchiveService::class)->nouvelleVersionContenu(
                            $this->getOwnerRecord(),
                            $contenu,
                            is_object($fichier) ? $fichier->getClientOriginalName() : basename($fichier),
                            is_object($fichier) ? $fichier->getMimeType() : 'application/octet-stream',
                            [],
                            auth()->user(),
                        );

                        $this->dispatch('refresh');
                    }),
            ])
            ->recordActions([
                Action::make('telecharger')
                    ->label('Télécharger')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn (DocumentVersion $record) => Storage::disk(config('archive.disk', 'public'))->download($record->file_path, $record->file_name)),
            ])
            ->paginated(false);
    }
}
