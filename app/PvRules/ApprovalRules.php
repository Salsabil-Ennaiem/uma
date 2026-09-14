<?php

namespace App\PvRules;

use SalsabilEnnaiem\PvModule\Contracts\ApprovalRules as ApprovalRulesContract;
use SalsabilEnnaiem\PvModule\Models\Pv;

/**
 * Seuils d'approbation paramétrables par config (uma.php).
 * - mode 'unanimous' (défaut) : tous les validateurs doivent valider.
 * - mode 'quorum' : un pourcentage (quorum_percent) suffit.
 * Règle héritée de Voyager : la validation du créateur approuve le PV.
 */
class ApprovalRules implements ApprovalRulesContract
{
    public function isApproved(Pv $pv): bool
    {
        if ($pv->validationsValidees()->where('user_id', $pv->created_by)->exists()) {
            return true;
        }

        $total = $pv->validations()->count();

        if ($total === 0) {
            return false;
        }

        $validees = $pv->validationsValidees()->count();

        if (config('uma.approval.mode') === 'quorum') {
            $needed = (int) ceil($total * (int) config('uma.approval.quorum_percent', 100) / 100);

            return $validees >= $needed;
        }

        return $validees === $total;
    }
}
