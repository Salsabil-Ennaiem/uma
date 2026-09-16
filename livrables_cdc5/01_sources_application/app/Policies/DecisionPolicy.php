<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Decision;
use App\Models\User;

class DecisionPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->role !== null;
    }

    public function view(User $actor, Decision $decision): bool
    {
        if ($actor->role === UserRole::Admin) {
            return true;
        }

        // Le doctorant concerné voit sa décision (mallette par année d'inscription).
        if ($actor->role === UserRole::Doctorant && $decision->dossier?->doctorant_id === $actor->getKey()) {
            return true;
        }

        // Membres / encadrement de la commission concernée.
        $commission = $decision->reunion?->commission;

        return $commission?->estMembre($actor)
            || $commission?->estPresident($actor)
            || $actor->role === UserRole::GestionnaireEcole;
    }

    public function create(User $actor): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::AgentAdministration,
            UserRole::PresidentCommission,
        ], true);
    }

    public function update(User $actor): bool
    {
        return $this->create($actor);
    }

    public function delete(User $actor): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::AgentAdministration,
            UserRole::PresidentCommission,
        ], true);
    }
}
