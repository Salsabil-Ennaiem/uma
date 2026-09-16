<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Réclamation / ticket (Workflow C, CDC §1.10).
 * Les états sont pilotés par le moteur de workflow paramétrable ;
 * la priorité (très urgente / prioritaire / moyennement urgente) est
 * configurable via `config('workflow.reclamations.priorites')`.
 */
class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = [
        'commission_id',
        'user_id',
        'type',
        'objet',
        'contenu',
        'urgence',
        'statut',
        'closed_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
        ];
    }

    public function commission(): BelongsTo
    {
        return $this->belongsTo(Commission::class);
    }

    public function deposant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(ReclamationDiscussion::class, 'reclamation_id');
    }

    public function isCloturee(): bool
    {
        return $this->closed_at !== null;
    }

    public function workflowInstances(): MorphMany
    {
        return $this->morphMany(WorkflowInstance::class, 'subject');
    }
}