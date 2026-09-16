<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Reunion;
use App\Models\User;
use SalsabilEnnaiem\PvModule\Contracts\CanManagePv;

/**
 * RBAC des réunions de commission (CDC §réunions).
 * - Admin : tout.
 * - Président de commission : gère les réunions de SA commission.
 * - Gestionnaire d'école : gère les réunions (périmètre école, fail-closed).
 * - Agent administratif : crée les réunions, enregistre présence/décisions.
 * - Membre de commission : voit et répond aux réunions où il est invité.
 * - Rôle absent/inexistant : refus (fail-closed).
 */
class ReunionPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->role !== null;
    }

    public function view(User $actor, Reunion $reunion): bool
    {
        if ($actor->role === UserRole::Admin) {
            return true;
        }

        $commission = $reunion->commission;

        if ($commission === null) {
            return false;
        }

        if ($this->manageur($actor, $reunion)) {
            return true;
        }

        // Membre de la commission concernée.
        if ($commission->estMembre($actor)) {
            return true;
        }

        // Invité à cette réunion.
        return $reunion->invitations()
            ->where('participant_id', $actor->getKey())
            ->exists();
    }

    public function create(User $actor): bool
    {
        return $this->createur($actor);
    }

    public function update(User $actor, Reunion $reunion): bool
    {
        if ($actor->role === UserRole::Admin) {
            return true;
        }

        if ($reunion->commission?->estPresident($actor)) {
            return true;
        }

        if ($actor->role === UserRole::AgentAdministration) {
            return in_array($reunion->statut?->value, ['brouillon', 'planifiee'], true);
        }

        return $actor->role === UserRole::GestionnaireEcole;
    }

    public function delete(User $actor, Reunion $reunion): bool
    {
        if ($actor->role === UserRole::Admin) {
            return true;
        }

        if ($reunion->commission?->estPresident($actor)) {
            return true;
        }

        return $actor->role === UserRole::GestionnaireEcole;
    }

    public function restore(User $actor, Reunion $reunion): bool
    {
        return $actor->role === UserRole::Admin;
    }

    public function forceDelete(User $actor, Reunion $reunion): bool
    {
        return $actor->role === UserRole::Admin;
    }

    public function planifier(User $actor, Reunion $reunion): bool
    {
        return $this->changerStatut($actor, $reunion);
    }

    public function demarrer(User $actor, Reunion $reunion): bool
    {
        return $this->changerStatut($actor, $reunion);
    }

    public function terminer(User $actor, Reunion $reunion): bool
    {
        return $this->changerStatut($actor, $reunion);
    }

    public function annuler(User $actor, Reunion $reunion): bool
    {
        return $this->changerStatut($actor, $reunion);
    }

    public function enregistrerPresence(User $actor, Reunion $reunion): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::AgentAdministration,
            UserRole::GestionnaireEcole,
        ], true)
            || $reunion->commission?->estPresident($actor) === true;
    }

    public function gererDecisions(User $actor, Reunion $reunion): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::AgentAdministration,
        ], true)
            || $reunion->commission?->estPresident($actor) === true;
    }

    public function genererPv(User $actor, Reunion $reunion): bool
    {
        if (! $reunion->estPassee()) {
            return false;
        }

        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
            UserRole::AgentAdministration,
        ], true)
            && app(CanManagePv::class)->canCreate($actor);
    }

    protected function createur(User $actor): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
            UserRole::AgentAdministration,
        ], true);
    }

    protected function changerStatut(User $actor, Reunion $reunion): bool
    {
        if ($actor->role === UserRole::Admin) {
            return true;
        }

        if ($reunion->commission?->estPresident($actor)) {
            return true;
        }

        return $actor->role === UserRole::GestionnaireEcole;
    }

    protected function manageur(User $actor, Reunion $reunion): bool
    {
        return $this->createur($actor) && $reunion->commission !== null;
    }
}
