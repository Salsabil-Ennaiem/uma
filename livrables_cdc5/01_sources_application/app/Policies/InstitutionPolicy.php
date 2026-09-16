<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

/**
 * Policy « Institution » (Université → École doctorale → Établissement) :
 * lecture par les cadres institutionnels, écriture réservée à l'admin.
 */
class InstitutionPolicy
{
    public function viewAny(User $actor): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
        ], true);
    }

    public function view(User $actor): bool
    {
        return $this->viewAny($actor);
    }

    public function create(User $actor): bool
    {
        return $this->manage($actor);
    }

    public function update(User $actor): bool
    {
        return $this->manage($actor);
    }

    public function delete(User $actor): bool
    {
        return $this->manage($actor);
    }

    protected function manage(User $actor): bool
    {
        return $actor->role === UserRole::Admin;
    }
}
