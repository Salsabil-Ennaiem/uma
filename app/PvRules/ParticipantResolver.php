<?php

namespace App\PvRules;

use App\Models\Commission;
use SalsabilEnnaiem\PvModule\Defaults\DefaultParticipantResolver;

/**
 * Résolution des signataires depuis les entités métier UMA :
 * - contexte 'commission' -> membres + président de la commission ;
 * - contexte 'jury' -> liste explicite de membre(s) ;
 * - contexte 'participants' transmis tel quel (rétrocompat).
 * La normalisation des placements reste celle du package (héritée).
 */
class ParticipantResolver extends DefaultParticipantResolver
{
    public function resolveParticipants(mixed $actor, array $context): array
    {
        if (array_key_exists('participants', $context)) {
            return $context['participants'];
        }

        $type = $context['type'] ?? null;

        if ($type === 'commission') {
            $commission = Commission::find($context['commission_id'] ?? 0);

            if ($commission === null) {
                return [];
            }

            $ids = $commission->membres()->pluck('users.id')->all();

            if ($commission->president_id) {
                $ids[] = $commission->president_id;
            }

            return array_values(array_unique(array_map('intval', $ids)));
        }

        if ($type === 'jury') {
            return array_map('intval', (array) ($context['membres'] ?? []));
        }

        return [];
    }
}
