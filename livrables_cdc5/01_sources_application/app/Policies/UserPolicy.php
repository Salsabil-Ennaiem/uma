<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

/**
 * Policy de reference : matrice des espaces du CDC Lot 2 §2.
 * Les espaces sont mappes sur UserRole ; chaque domaine (Commissions,
 * Reunions, Doctorat) ajoutera sa propre policy selon le meme schema.
 * Defaut fail-closed : role absent -> refus.
 */
class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->role === UserRole::Admin;
    }

    public function view(User $actor, User $subject): bool
    {
        return $this->viewAny($actor) || $actor->id === $subject->id;
    }

    public function create(User $actor): bool
    {
        return $this->viewAny($actor);
    }

    public function update(User $actor, User $subject): bool
    {
        return $this->viewAny($actor) || $actor->id === $subject->id;
    }

    public function updateRole(User $actor, User $subject): bool
    {
        return $this->viewAny($actor);
    }

    public function delete(User $actor, User $subject): bool
    {
        return $this->viewAny($actor) && $actor->id !== $subject->id;
    }
}
