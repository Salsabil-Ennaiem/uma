<?php

namespace App\PvSignatures;

use App\Contracts\SignatureStrategy;
use Illuminate\Support\Facades\Log;

/**
 * qualified : stub documenté (R2). Le signataire a besoin d'un certificat
 * qualité ; tant qu'aucun certificat n'est délivré, la signature est refusée
 * et l'événement est journalisé. Basculable par config sans toucher à la logique.
 */
class QualifiedSignatureStrategy implements SignatureStrategy
{
    public function mechanism(): string
    {
        return 'qualified';
    }

    public function supportsSigning(mixed $actor): bool
    {
        $allowed = (bool) config('uma.compliance.qualified.allow_without_certificate', false);

        Log::warning('SignatureStrategy[qualified]: certificat requis (R2)', [
            'user' => $actor?->getKey(),
            'allow_without_certificate' => $allowed,
        ]);

        return $allowed;
    }

    public function signToken(mixed $actor): array
    {
        return [
            'mechanism' => $this->mechanism(),
            'signed_at' => now()->toIso8601String(),
        ];
    }
}
