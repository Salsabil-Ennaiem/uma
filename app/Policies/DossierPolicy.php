<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class DossierPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->role !== null;
    }

    public function create(User $actor): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::AgentAdministration,
            UserRole::GestionnaireEcole,
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
        ], true);
    }
}
