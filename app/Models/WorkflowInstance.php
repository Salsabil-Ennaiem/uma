<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Instance d'un workflow paramétrable : applique une définition à un sujet
 * métier (ex. un dossier ou une réclamation) et suit l'état courant.
 */
class WorkflowInstance extends Model
{
    protected $table = 'uma_workflow_instances';

    use HasFactory;

    protected $fillable = [
        'workflow_definition_id',
        'subject_type',
        'subject_id',
        'current_state',
        'data',
        'status',
        'started_at',
        'completed_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class, 'workflow_definition_id');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function auditTrails(): HasMany
    {
        return $this->hasMany(WorkflowAuditTrail::class, 'workflow_instance_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function dataGet(string $key, mixed $default = null): mixed
    {
        return data_get($this->data, $key, $default);
    }
}
