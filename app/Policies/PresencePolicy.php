<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class PresencePolicy
{
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
}
