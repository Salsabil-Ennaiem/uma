<?php

namespace App\PvRules;

use App\Enums\UserRole;
use App\Models\User;
use App\PvSignatures\SignatureResolver;
use Illuminate\Support\Facades\Log;
use SalsabilEnnaiem\PvModule\Contracts\CanManagePv;
use SalsabilEnnaiem\PvModule\Models\Pv;
use SalsabilEnnaiem\PvModule\Models\PvValidation;

/**
 * RBAC métier UMA branchée sur le contrat CanManagePv du package.
 * Fail-closed : rôle absent/inexistant -> refus + journalisation discrète.
 */
class PvRules implements CanManagePv
{
    public function canCreate(mixed $actor): bool
    {
        return $this->authorized($actor, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
            UserRole::AgentAdministration,
        ], 'create');
    }

    public function canUpdate(mixed $actor, Pv $pv): bool
    {
        return $this->authorized($actor, [
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
        ], 'update')
            && ($this->isCreator($actor, $pv) || $this->inScope($actor, $pv));
    }

    public function canSend(mixed $actor, Pv $pv): bool
    {
        if ($this->authorized($actor, [UserRole::Admin], 'send')) {
            return true;
        }

        $allowed = $this->authorized($actor, [
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
        ], 'send')
            && ($this->isCreator($actor, $pv) || $this->inScope($actor, $pv));

        if ($allowed) {
            return true;
        }

        return $this->authorized($actor, [UserRole::AgentAdministration], 'send')
            && $this->isCreator($actor, $pv);
    }

    public function canValidate(mixed $actor, Pv $pv): bool
    {
        if (! $this->authorized($actor, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
            UserRole::MembreCommission,
            UserRole::DirecteurThese,
        ], 'validate')) {
            return false;
        }

        return $this->isValidator($actor, $pv);
    }

    public function canSign(mixed $actor, Pv $pv): bool
    {
        if (! $this->canValidate($actor, $pv)) {
            return false;
        }

        return app(SignatureResolver::class)->strategy()->supportsSigning($actor);
    }

    public function canDelete(mixed $actor, Pv $pv): bool
    {
        $allowed = $this->authorized($actor, [
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
        ], 'delete')
            && ($this->isCreator($actor, $pv) || $this->inScope($actor, $pv));

        if ($this->authorized($actor, [UserRole::Admin], 'delete')) {
            return true;
        }

        return $allowed;
    }

    public function canDownload(mixed $actor, Pv $pv): bool
    {
        if ($this->canUpdate($actor, $pv)) {
            return true;
        }

        if ($this->authorized($actor, [UserRole::Admin], 'download')) {
            return true;
        }

        return $this->isParticipant($actor, $pv);
    }

    public function canManageTemplates(mixed $actor): bool
    {
        return $this->authorized($actor, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
            UserRole::AgentAdministration,
        ], 'templates');
    }

    public function roleOf(mixed $actor): ?UserRole
    {
        if (! $actor instanceof User) {
            return null;
        }

        $role = $actor->role;

        return $role instanceof UserRole ? $role : null;
    }

    protected function authorized(mixed $actor, array $roles, string $action): bool
    {
        $role = $this->roleOf($actor);

        if ($role === null || ! in_array($role, $roles, true)) {
            if ($actor !== null && $actor instanceof User) {
                Log::info('PvRules: refus '.$action, [
                    'user' => $actor->getKey(),
                    'role' => $role?->value,
                ]);
            }

            return false;
        }

        return true;
    }

    protected function isCreator(mixed $actor, Pv $pv): bool
    {
        return $actor !== null && $pv->created_by === $actor->getKey();
    }

    protected function isParticipant(mixed $actor, Pv $pv): bool
    {
        if ($actor === null) {
            return false;
        }

        return $pv->validations()->where('user_id', $actor->getKey())->exists();
    }

    protected function isValidator(mixed $actor, Pv $pv): bool
    {
        if ($actor === null) {
            return false;
        }

        return $pv->validations()
            ->where('user_id', $actor->getKey())
            ->where('statut', PvValidation::STATUT_EN_ATTENTE)
            ->exists();
    }

    /**
     * Périmètre métier : l'acteur appartient à la commission à laquelle
     * le document est rattaché (source_type = 'commission'). A défaut de
     * rattachement, le périmètre se réduit au créateur.
     */
    protected function inScope(mixed $actor, Pv $pv): bool
    {
        if (! $actor instanceof User) {
            return false;
        }

        if ($pv->source_type !== 'commission' || ! $pv->source_id) {
            return $this->isCreator($actor, $pv);
        }

        return $actor->commissions()->whereKey($pv->source_id)->exists()
            || $actor->commissionsPresidees()->whereKey($pv->source_id)->exists();
    }
}
