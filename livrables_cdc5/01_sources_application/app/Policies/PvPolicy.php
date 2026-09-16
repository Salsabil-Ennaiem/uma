<?php

namespace App\Policies;

use App\Models\User;
use SalsabilEnnaiem\PvModule\Contracts\CanManagePv;
use SalsabilEnnaiem\PvModule\Models\Pv;

/**
 * Policy des documents (Pv) : délègue TOUT au contrat CanManagePv du package.
 * Aucune règle dupliquée ici — le contrat est la source de vérité.
 */
class PvPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->role !== null;
    }

    public function create(User $actor): bool
    {
        return app(CanManagePv::class)->canCreate($actor);
    }

    public function update(User $actor, Pv $pv): bool
    {
        return app(CanManagePv::class)->canUpdate($actor, $pv);
    }

    public function delete(User $actor, Pv $pv): bool
    {
        return app(CanManagePv::class)->canDelete($actor, $pv);
    }

    public function send(User $actor, Pv $pv): bool
    {
        return app(CanManagePv::class)->canSend($actor, $pv);
    }

    public function validate(User $actor, Pv $pv): bool
    {
        return app(CanManagePv::class)->canValidate($actor, $pv);
    }

    public function sign(User $actor, Pv $pv): bool
    {
        return app(CanManagePv::class)->canSign($actor, $pv);
    }

    public function download(User $actor, Pv $pv): bool
    {
        return app(CanManagePv::class)->canDownload($actor, $pv);
    }
}
