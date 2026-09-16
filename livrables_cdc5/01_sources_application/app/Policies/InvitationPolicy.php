<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Invitation;
use App\Models\User;

class InvitationPolicy
{
    public function view(User $actor, Invitation $invitation): bool
    {
        if ($actor->role === UserRole::Admin) {
            return true;
        }

        return $invitation->participant_id === $actor->getKey();
    }

    public function respond(User $actor, Invitation $invitation): bool
    {
        if ($invitation->participant_id !== $actor->getKey()) {
            return false;
        }

        // Réponse possible uniquement avant le début de la réunion.
        return $invitation->reunion->date_debut->gt(now())
            && $invitation->statut->value === 'en_attente';
    }
}
