<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\RapportEtat;
use App\Models\User;

class RapportEtatPolicy
{
    public function viewAny(User $actor): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
            UserRole::AgentAdministration,
        ], true);
    }

    public function view(User $actor, RapportEtat $etat): bool
    {
        return $this->viewAny($actor);
    }

    public function create(User $actor): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
        ], true);
    }

    public function update(User $actor, RapportEtat $etat): bool
    {
        return $this->create($actor);
    }

    public function delete(User $actor, RapportEtat $etat): bool
    {
        return $this->create($actor);
    }

    public function generer(User $actor, RapportEtat $etat): bool
    {
        return $this->viewAny($actor) && $etat->is_active;
    }
}
