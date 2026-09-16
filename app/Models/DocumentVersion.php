<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

/**
 * Version d'un document archivé (versioning immuable — CDC §1.14, règle d'or P7).
 * Une version est créée une seule fois : toute tentative de mise à jour ou de
 * suppression est refusée au niveau du modèle (append-only).
 */
class DocumentVersion extends Model
{
    protected $table = 'uma_document_versions';

    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'document_id',
        'version',
        'file_path',
        'file_name',
        'mime_type',
        'size',
        'hash',
        'metadata',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function save(array $options = []): bool
    {
        if ($this->exists) {
            throw new LogicException('Une version de document est immuable.');
        }

        return parent::save($options);
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new LogicException('Une version de document est immuable.');
    }

    public function delete(): ?bool
    {
        throw new LogicException('Une version de document est immuable.');
    }
}
