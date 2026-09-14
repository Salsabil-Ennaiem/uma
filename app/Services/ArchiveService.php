<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Archivage des pièces du dossier numérique (mallette, CDC §1.14).
 * - Les pièces sont stockées sur le disque public de l'app (pas le package) ;
 * - versioning immuable : un hash SHA-256 est calculé, une version n'est jamais écrasée ;
 * - archivage dès la création (« historique doctorant ») ;
 * - chaque action est tracée dans audit_logs.
 */
class ArchiveService
{
    public function __construct(private AuditLogger $audit) {}

    public function disk(): string
    {
        return (string) config('archive.disk', 'public');
    }

    /**
     * Archive un fichier uploadé : crée le document + version v1.
     */
    public function archiverUpload(
        Model $documentable,
        string $type,
        string $label,
        UploadedFile $file,
        array $metadata = [],
        ?int $retentionMonths = null,
        ?string $description = null,
        ?User $actor = null,
    ): Document {
        return $this->creer(
            $documentable,
            $type,
            $label,
            (string) file_get_contents($file->getPathname()),
            $file->getClientOriginalName() ?: Str::slug($label).'.'.($file->extension() ?? 'bin'),
            $file->getMimeType(),
            $metadata,
            $retentionMonths,
            $description,
            $actor,
        );
    }

    /**
     * Archive depuis un chemin local existant (ex. PDF généré par le package).
     */
    public function archiverFichier(
        Model $documentable,
        string $type,
        string $label,
        string $cheminLocal,
        ?string $fileName = null,
        array $metadata = [],
        ?int $retentionMonths = null,
        ?string $description = null,
        ?User $actor = null,
    ): Document {
        $cheminLocal = str_starts_with($cheminLocal, 'private/') || str_starts_with($cheminLocal, 'public/')
            ? storage_path('app/'.$cheminLocal)
            : $cheminLocal;

        return $this->creer(
            $documentable,
            $type,
            $label,
            (string) file_get_contents($cheminLocal),
            $fileName ?? basename($cheminLocal),
            'application/pdf',
            $metadata,
            $retentionMonths,
            $description,
            $actor,
        );
    }

    /**
     * Archive un contenu brut (string binaire, ex. PDF en mémoire).
     */
    public function archiverContenu(
        Model $documentable,
        string $type,
        string $label,
        string $contenu,
        string $extension = 'pdf',
        ?string $fileName = null,
        array $metadata = [],
        ?int $retentionMonths = null,
        ?string $description = null,
        ?User $actor = null,
    ): Document {
        return $this->creer(
            $documentable,
            $type,
            $label,
            $contenu,
            $fileName ?? Str::slug($label).'.'.$extension,
            'application/'.$extension,
            $metadata,
            $retentionMonths,
            $description,
            $actor,
        );
    }

    /**
     * Ajoute une version immuable (vN+1) à un document existant.
     */
    public function nouvelleVersionContenu(
        Document $document,
        string $contenu,
        ?string $fileName = null,
        ?string $mime = 'application/pdf',
        array $metadata = [],
        ?User $actor = null,
    ): DocumentVersion {
        $hash = hash('sha256', $contenu);
        $ext = pathinfo($fileName ?? 'fichier.bin', PATHINFO_EXTENSION) ?: 'bin';
        $path = $this->chemin($document, $hash, $ext);

        $this->stocker($path, $contenu);

        return DB::transaction(function () use ($document, $hash, $path, $fileName, $mime, $contenu, $metadata, $actor) {
            $ext = $mime === 'application/pdf' ? 'pdf' : (pathinfo($fileName ?? 'fichier.bin', PATHINFO_EXTENSION) ?: 'bin');

            $version = DocumentVersion::create([
                'document_id' => $document->getKey(),
                'version' => $document->versions()->max('version') + 1,
                'file_path' => $path,
                'file_name' => $fileName ?? Str::slug($document->label).'.'.$ext,
                'mime_type' => $mime,
                'size' => strlen($contenu),
                'hash' => $hash,
                'metadata' => $metadata,
                'created_by' => $actor?->getKey() ?? auth()->id(),
            ]);

            $this->audit->log('document.version.create', $document, null, [
                'version' => $version->version,
                'hash' => $hash,
                'size' => $version->size,
            ], $actor);

            return $version;
        });
    }

    /**
     * Rétention par défaut d'un type de document (mois).
     */
    public function retentionParDefaut(string $type): ?int
    {
        return config("archive.retention.{$type}", config('archive.retention.default'));
    }

    protected function creer(
        Model $documentable,
        string $type,
        string $label,
        string $contenu,
        string $fileName,
        ?string $mime,
        array $metadata,
        ?int $retentionMonths,
        ?string $description,
        ?User $actor,
    ): Document {
        $retentionMonths ??= $this->retentionParDefaut($type);

        $hash = hash('sha256', $contenu);
        $ext = pathinfo($fileName, PATHINFO_EXTENSION) ?: 'bin';
        $path = $this->chemin($documentable, $hash, $ext);

        $this->stocker($path, $contenu);

        return DB::transaction(function () use ($documentable, $type, $label, $description, $retentionMonths, $fileName, $mime, $contenu, $metadata, $hash, $path, $actor) {
            $actor ??= auth()->user();

            /** @var Document $document */
            $document = Document::create([
                'documentable_type' => $documentable->getMorphClass(),
                'documentable_id' => $documentable->getKey(),
                'type' => $type,
                'label' => $label,
                'description' => $description,
                'retention_months' => $retentionMonths,
                'retention_until' => $retentionMonths !== null ? now()->addMonths($retentionMonths)->toDateString() : null,
                'is_archived' => true,
                'created_by' => $actor?->getKey(),
            ]);

            $document->versions()->create([
                'version' => 1,
                'file_path' => $path,
                'file_name' => $fileName,
                'mime_type' => $mime,
                'size' => strlen($contenu),
                'hash' => $hash,
                'metadata' => $metadata,
                'created_by' => $actor?->getKey(),
            ]);

            $this->audit->log('document.create', $document, null, [
                'type' => $type,
                'documentable' => $document->documentable_type.'#'.$document->documentable_id,
                'hash' => $hash,
                'retention_until' => $document->retention_until?->toDateString(),
            ], $actor);

            $document->load('versions');

            return $document;
        });
    }

    protected function chemin(Model|Document $entity, string $hash, string $ext): string
    {
        $morph = str($entity->getMorphClass())->afterLast('\\')->slug()->lower()->toString();

        $id = $entity instanceof Document
            ? $entity->documentable_id
            : $entity->getKey();

        return implode('/', [
            (string) config('archive.prefix', 'documents'),
            $morph,
            (string) $id,
            $hash.'.'.$ext,
        ]);
    }

    protected function stocker(string $path, string $contenu): void
    {
        try {
            Storage::disk($this->disk())->put($path, $contenu);
        } catch (Throwable $e) {
            throw new \RuntimeException("Impossible de stocker la pièce « {$path} » : {$e->getMessage()}", 0, $e);
        }
    }
}
