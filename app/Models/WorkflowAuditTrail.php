<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

/**
 * Trace d'exécution d'une transition de workflow.
 * Append-only : toute écriture sur une ligne existante est refusée
 * (traçabilité des parcours, exigence P7 transposée au moteur).
 */
class WorkflowAuditTrail extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'workflow_instance_id',
        'from_state',
        'to_state',
        'transition_code',
        'actor_id',
        'payload',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function instance(): BelongsTo
    {
        return $this->belongsTo(WorkflowInstance::class, 'workflow_instance_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function save(array $options = []): bool
    {
        if ($this->exists) {
            throw new LogicException('Une trace de workflow est immuable.');
        }

        return parent::save($options);
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new LogicException('Une trace de workflow est immuable.');
    }

    public function delete(): ?bool
    {
        throw new LogicException('Une trace de workflow est immuable.');
    }
}