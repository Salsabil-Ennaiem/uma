<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Garde paramétrable d'une transition de workflow.
 * La règle (`rule`) est un code interprété par le garde-règles du moteur
 * (ex. `role`, `subject.data`, `subject.document`, `contract`, `no_overlap`).
 */
class WorkflowGuard extends Model
{
    protected $table = 'uma_workflow_guards';

    use HasFactory;

    protected $fillable = [
        'workflow_transition_id',
        'rule',
        'params',
        'error_message',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'params' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function transition(): BelongsTo
    {
        return $this->belongsTo(WorkflowTransition::class, 'workflow_transition_id');
    }
}
