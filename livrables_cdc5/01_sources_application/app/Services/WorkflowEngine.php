<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Reclamation;
use App\Models\User;
use App\Models\WorkflowAuditTrail;
use App\Models\WorkflowDefinition;
use App\Models\WorkflowInstance;
use App\Models\WorkflowTransition;
use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Moteur de workflow paramétrable en base (P8, Étape 6).
 *
 * API stable, sans dépendance à l'UI :
 *  - start(): démarre une instance sur un sujet métier ;
 *  - availableTransitions(): transitions applicables depuis l'état courant ;
 *  - can(): vérifie qu'une transition est tentable par un acteur ;
 *  - apply(): exécute une transition (gardes → état → actions → traces).
 *
 * Transitions, rôles, gardes, actions et notifications sont déclarés en base
 * (workflow_definitions / workflow_transitions / workflow_guards) : aucune
 * transition nouvelle ne requiert de modification de code.
 */
class WorkflowEngine
{
    public function __construct(
        private WorkflowGuardResolver $guards,
        private WorkflowActions $actions,
        private AuditLogger $audit,
    ) {}

    /**
     * Démarre une instance de workflow sur un sujet métier.
     */
    public function start(WorkflowDefinition $definition, Model $subject, array $data = [], ?User $actor = null): WorkflowInstance
    {
        return DB::transaction(function () use ($definition, $subject, $data, $actor) {
            $instance = new WorkflowInstance([
                'workflow_definition_id' => $definition->getKey(),
                'subject_type' => $subject->getMorphClass(),
                'subject_id' => $subject->getKey(),
                'current_state' => $definition->initial_state,
                'data' => $data,
                'status' => 'active',
                'started_at' => now(),
                'created_by' => $actor?->getKey(),
            ]);
            $instance->save();

            $this->trace($instance, $instance->current_state, $instance->current_state, 'start', $actor, [
                'subject_type' => $instance->subject_type,
                'subject_id' => $instance->subject_id,
            ]);

            $this->audit->log('workflow.start', $instance, null, [
                'definition' => $definition->code,
                'initial_state' => $definition->initial_state,
            ], $actor);

            return $instance;
        });
    }

    /**
     * Transitions applicables pour l'état courant de l'instance, en filtrant
     * celles dont l'acteur a le rôle et dont toutes les gardes passent.
     */
    public function availableTransitions(WorkflowInstance $instance, ?User $actor = null): Collection
    {
        if (! $instance->isActive()) {
            return collect();
        }

        return $instance->definition->transitions
            ->where('from_state', $instance->current_state)
            ->where('is_active', true)
            ->sortBy('sort')
            ->values()
            ->filter(fn (WorkflowTransition $t) => $this->can($instance, $t, $actor));
    }

    /**
     * Une transition est-elle tentable par l'acteur ?
     */
    public function can(WorkflowInstance $instance, WorkflowTransition|string $transition, ?User $actor = null): bool
    {
        try {
            $transition = $this->resolveTransition($instance, $transition);

            if (! $instance->isActive()) {
                return false;
            }

            if ($transition->from_state !== $instance->current_state || ! $transition->is_active) {
                return false;
            }

            $role = $actor?->role;
            $roleValue = $role?->value ?? null;
            $allowedRoles = $transition->rolesAutorises();

            return $roleValue === UserRole::Admin->value || in_array($roleValue, $allowedRoles, true);
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Exécute une transition de workflow.
     *
     * @throws DomainException si la transition est interdite (état, rôle, garde).
     */
    public function apply(WorkflowInstance $instance, WorkflowTransition|string $transition, array $payload = [], ?User $actor = null): WorkflowInstance
    {
        $transition = $this->resolveTransition($instance, $transition);

        return DB::transaction(function () use ($instance, $transition, $payload, $actor) {
            $this->guard($instance, $transition, $actor, $payload);

            $from = $instance->current_state;
            $to = $transition->to_state;

            $instance->data = array_merge((array) $instance->data, $payload);

            $instance->current_state = $to;

            if ($instance->definition->isTerminalState($to)) {
                $instance->status = 'completed';
                $instance->completed_at = now();
            }

            // Actions de bord déclarées (ex. notification, génération d'attestation),
            // avec la donnée de transition déjà fusionnée dans l'instance.
            foreach ((array) $transition->actions as $actionKey) {
                $this->actions->run($actionKey, $instance, $transition, $payload, $actor);
            }

            $instance->save();

            $this->trace($instance, $from, $to, $transition->code, $actor, $payload);
            $this->audit->log('workflow.transition', $instance, ['state' => $from], [
                'definition' => $instance->definition->code,
                'transition' => $transition->code,
                'state' => $to,
            ], $actor);

            // Notifications des parties prenantes déclarées en base.
            $this->notify($instance, $transition, $actor);

            return $instance->fresh();
        });
    }

    /**
     * Compare l'état courant puis exécute les gardes (rôles + règles).
     *
     * @throws DomainException
     */
    public function guard(WorkflowInstance $instance, WorkflowTransition|string $transition, ?User $actor = null, array $payload = []): void
    {
        $transition = $this->resolveTransition($instance, $transition);

        if (! $instance->isActive()) {
            throw new DomainException('Le workflow est déjà terminé.');
        }

        if ($transition->from_state !== $instance->current_state) {
            throw new DomainException(
                "Transition {$transition->code} non applicable depuis l'état « {$instance->current_state} »."
            );
        }

        if (! $transition->is_active) {
            throw new DomainException("La transition {$transition->code} est désactivée.");
        }

        $role = $actor?->role;
        $roleValue = $role?->value ?? null;
        $allowedRoles = $transition->rolesAutorises();

        if ($roleValue !== UserRole::Admin->value && ! in_array($roleValue, $allowedRoles, true)) {
            throw new DomainException(
                "Rôle « {$roleValue} » non autorisé pour la transition {$transition->code}."
            );
        }

        foreach ($transition->guards->where('is_active', true) as $guard) {
            $result = $this->guards->evaluate($guard, $instance, $actor, $payload);

            if (! $result['allowed']) {
                throw new DomainException($result['message'] ?? "Garde {$guard->rule} refusée.");
            }
        }
    }

    protected function resolveTransition(WorkflowInstance $instance, WorkflowTransition|string $transition): WorkflowTransition
    {
        if ($transition instanceof WorkflowTransition) {
            return $transition;
        }

        $found = $instance->definition->transitions()
            ->where('code', $transition)
            ->first();

        if ($found === null) {
            throw new DomainException("Transition « {$transition} » inconnue.");
        }

        return $found;
    }

    protected function trace(WorkflowInstance $instance, ?string $from, string $to, string $code, ?User $actor = null, array $payload = []): void
    {
        $trail = new WorkflowAuditTrail([
            'workflow_instance_id' => $instance->getKey(),
            'from_state' => $from,
            'to_state' => $to,
            'transition_code' => $code,
            'actor_id' => $actor?->getKey(),
            'payload' => $payload,
        ]);
        $trail->save();
    }

    /**
     * Notifications de transition déclarées en base.
     * Format : [{ "role": "doctorant", "message": "Votre dossier a avancé." }, ...]
     */
    protected function notify(WorkflowInstance $instance, WorkflowTransition $transition, ?User $actor = null): void
    {
        foreach ((array) $transition->notifications as $spec) {
            $roleKey = $spec['role'] ?? null;
            $message = $spec['message'] ?? "Transition {$transition->code} : {$instance->current_state}";

            $recipients = $this->resolveRecipients($instance, $roleKey);

            foreach ($recipients as $user) {
                $this->actions->notifyUser($user, $instance, $message);
            }
        }
    }

    protected function resolveRecipients(WorkflowInstance $instance, ?string $roleKey): array
    {
        $subject = $instance->subject;

        // Destinataires dérivés du sujet (doctorant pour un dossier, déposant pour une réclamation).
        if ($subject instanceof \App\Models\Dossier && $subject->doctorant !== null) {
            $doctorant = $subject->doctorant;

            if ($roleKey === null || $roleKey === UserRole::Doctorant->value) {
                return [$doctorant];
            }
        }

        if ($subject instanceof Reclamation && $subject->deposant !== null) {
            $deposant = $subject->deposant;

            if ($roleKey === null || $roleKey === UserRole::Doctorant->value) {
                return [$deposant];
            }
        }

        if ($roleKey !== null) {
            return User::query()->where('role', $roleKey)->limit(20)->get()->all();
        }

        return [];
    }
}