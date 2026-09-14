<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Dossier numérique (mallette, CDC §1.14).
 * Rattaché de façon polymorphe à une entité (doctorant, dossier, réunion, décision).
 * Archivé dès sa création ; une pièce n'est jamais écrasée (versions immuables).
 */
class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentable_type',
        'documentable_id',
        'type',
        'label',
        'description',
        'retention_months',
        'retention_until',
        'is_archived',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_archived' => 'boolean',
            'retention_until' => 'date',
        ];
    }

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class)->orderBy('version');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lastVersion(): ?DocumentVersion
    {
        return $this->versions()->reorder('version', 'desc')->first();
    }

    public function estExpire(): bool
    {
        return $this->retention_until !== null && $this->retention_until->isPast();
    }
}
