<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Définition paramétrable d'un workflow métier (CDC §1.12).
 * Les états sont stockés dans la colonne `states` (json) : toute transition
 * déclarable en base n'exige aucune modification de code.
 */
class WorkflowDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'subject_type',
        'states',
        'initial_state',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'states' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function transitions(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'workflow_definition_id');
    }

    public function instances(): HasMany
    {
        return $this->hasMany(WorkflowInstance::class, 'workflow_definition_id');
    }

    public function statesList(): array
    {
        return (array) $this->states;
    }

    public function isTerminalState(string $state): bool
    {
        return $state === collect($this->statesList())->last();
    }
}