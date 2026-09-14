<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->role !== null;
    }

    public function view(User $actor, Document $document): bool
    {
        return $actor->role !== null;
    }

    public function download(User $actor, Document $document): bool
    {
        return $this->view($actor, $document);
    }

    public function create(User $actor): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
            UserRole::AgentAdministration,
            UserRole::Doctorant,
        ], true);
    }

    public function update(User $actor, Document $document): bool
    {
        return $actor->role === UserRole::Admin;
    }

    public function delete(User $actor, Document $document): bool
    {
        return $actor->role === UserRole::Admin;
    }
}
