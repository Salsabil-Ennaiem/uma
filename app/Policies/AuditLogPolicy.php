<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\User;

/**
 * Journal d'audit : en lecture seule pour l'administrateur.
 * Toute mutation est refusée (append-only) — le champs d'application est aussi
 * garanti au niveau du modèle (AuditLog::save/update/delete lèvent une exception).
 */
class AuditLogPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->role === UserRole::Admin;
    }

    public function view(User $actor, AuditLog $log): bool
    {
        return $actor->role === UserRole::Admin;
    }

    public function create(User $actor): bool
    {
        return false;
    }

    public function update(User $actor, AuditLog $log): bool
    {
        return false;
    }

    public function delete(User $actor, AuditLog $log): bool
    {
        return false;
    }

    public function restore(User $actor, AuditLog $log): bool
    {
        return false;
    }

    public function forceDelete(User $actor, AuditLog $log): bool
    {
        return false;
    }
}
