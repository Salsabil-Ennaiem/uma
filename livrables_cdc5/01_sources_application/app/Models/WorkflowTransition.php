<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Transition déclarée en base entre deux états d'un workflow.
 * Les gardes (WorkflowGuard) et les rôles autorisés (json `roles`) pilotent
 * l'exécution ; aucune logique de transition n'est codée en dur dans Filament.
 */
class WorkflowTransition extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_definition_id',
        'code',
        'label',
        'from_state',
        'to_state',
        'roles',
        'actions',
        'notifications',
        'sort',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'roles' => 'array',
            'actions' => 'array',
            'notifications' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class, 'workflow_definition_id');
    }

    public function guards(): HasMany
    {
        return $this->hasMany(WorkflowGuard::class, 'workflow_transition_id');
    }

    public function rolesAutorises(): array
    {
        return array_values(array_filter((array) $this->roles));
    }
}