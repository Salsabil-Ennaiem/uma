<?php

namespace App\PvSignatures;

use App\Contracts\SignatureStrategy;

/**
 * Resolveur unique (R2) : lit config('uma.compliance.signature_driver')
 * et retourne la stratégie active. Aucun if/else de mécanisme dans les workflows.
 */
class SignatureResolver
{
    public function strategy(): SignatureStrategy
    {
        $driver = (string) config('uma.compliance.signature_driver', 'simple_image');

        if ($driver === 'qualified') {
            return app(QualifiedSignatureStrategy::class);
        }

        return app(SimpleImageSignatureStrategy::class);
    }

    public function mechanism(): string
    {
        return $this->strategy()->mechanism();
    }
}
