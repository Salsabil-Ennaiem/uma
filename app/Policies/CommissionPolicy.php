<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Commission;
use App\Models\User;

/**
 * Policy Commission (CDC §2 espace « Commissions »).
 * Fail-closed : le président gère sa commission, les membres n'y accèdent
 * que s'ils en sont membres. Toute commission hors périmètre -> refus (IDOR).
 */
class CommissionPolicy
{
    public function viewAny(User $actor): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
            UserRole::MembreCommission,
        ], true);
    }

    public function view(User $actor, Commission $commission): bool
    {
        if ($actor->role === UserRole::Admin) {
            return true;
        }

        if ($actor->role === UserRole::GestionnaireEcole) {
            return $this->inEcole($actor, $commission);
        }

        return $commission->estMembre($actor) || $commission->estPresident($actor);
    }

    public function create(User $actor): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
        ], true);
    }

    public function update(User $actor, Commission $commission): bool
    {
        if ($actor->role === UserRole::Admin) {
            return true;
        }

        if ($commission->estPresident($actor)) {
            return true;
        }

        return $actor->role === UserRole::GestionnaireEcole && $this->inEcole($actor, $commission);
    }

    public function delete(User $actor, Commission $commission): bool
    {
        return $actor->role === UserRole::Admin;
    }

    public function manageMembers(User $actor, Commission $commission): bool
    {
        return $this->update($actor, $commission);
    }

    protected function inEcole(User $actor, Commission $commission): bool
    {
        return $commission->etablissement?->ecole_doctorale_id !== null
            && $actor->etablissementDirecteur()
                ->whereKey($commission->etablissement_id)
                ->exists();
    }
}
