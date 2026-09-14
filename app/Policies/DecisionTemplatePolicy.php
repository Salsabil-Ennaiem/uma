<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\DecisionTemplate;
use App\Models\User;

/**
 * Policy des modèles de décision (P4) : création réservée aux gestionnaires
 * de commissions ; un président ne peut toucher que les modèles de sa propre
 * commission (anti-IDOR). Les modèles globaux (commission_id null) restent
 * visibles en lecture par les cadres de commission.
 */
class DecisionTemplatePolicy
{
    public function viewAny(User $actor): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
            UserRole::MembreCommission,
            UserRole::AgentAdministration,
        ], true);
    }

    public function view(User $actor, DecisionTemplate $template): bool
    {
        return $this->viewAny($actor)
            && $this->inScope($actor, $template);
    }

    public function create(User $actor): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
            UserRole::AgentAdministration,
        ], true);
    }

    public function update(User $actor, DecisionTemplate $template): bool
    {
        if ($actor->role === UserRole::Admin || $actor->role === UserRole::GestionnaireEcole) {
            return true;
        }

        if ($actor->role === UserRole::PresidentCommission) {
            return $this->inScope($actor, $template);
        }

        return false;
    }

    public function delete(User $actor, DecisionTemplate $template): bool
    {
        if ($this->update($actor, $template)) {
            return true;
        }

        return $actor->role === UserRole::AgentAdministration && $this->inScope($actor, $template);
    }

    protected function inScope(User $actor, DecisionTemplate $template): bool
    {
        if ($template->commission_id === null) {
            return true;
        }

        return $actor->commissions()->whereKey($template->commission_id)->exists()
            || $actor->commissionsPresidees()->whereKey($template->commission_id)->exists();
    }
}
