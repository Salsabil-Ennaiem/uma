<?php

namespace App\Models;

use App\Enums\DossierStatut;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dossier extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'doctorant_id',
        'commission_id',
        'objet',
        'description',
        'statut',
        'annee_inscription',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'statut' => DossierStatut::class,
        ];
    }

    public function doctorant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctorant_id');
    }

    public function commission(): BelongsTo
    {
        return $this->belongsTo(Commission::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reunions(): BelongsToMany
    {
        return $this->belongsToMany(Reunion::class, 'reunion_dossier')
            ->withPivot('position')
            ->withTimestamps();
    }

    public function decisions(): HasMany
    {
        return $this->hasMany(Decision::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function workflowInstances(): MorphMany
    {
        return $this->morphMany(WorkflowInstance::class, 'subject');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', DossierStatut::EnAttente->value);
    }
}
