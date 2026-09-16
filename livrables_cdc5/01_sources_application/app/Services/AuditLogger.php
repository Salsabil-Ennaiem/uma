<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Généralisation du journal d'audit (append-only) à toutes les entités
 * sensibles : réunions, décisions, documents, comptes doctorants.
 */
class AuditLogger
{
    /**
     * Enregistre une action d'audit. La ligne est inaltérable (append-only).
     */
    public function log(
        string $action,
        ?Model $entity = null,
        ?array $before = null,
        ?array $after = null,
        ?User $actor = null,
        bool $withIp = true,
    ): AuditLog {
        return AuditLog::log($action, $entity, $before, $after, $withIp, $actor);
    }

    /**
     * Trace une action sur un compte utilisateur (ex. création, changement de rôle).
     */
    public function logUserAccount(User $target, string $action, ?array $before = null, ?array $after = null, ?User $actor = null): AuditLog
    {
        return $this->log($action, $target, $before, $after, $actor);
    }
}
