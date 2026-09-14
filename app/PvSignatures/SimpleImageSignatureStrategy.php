<?php

namespace App\PvSignatures;

use App\Contracts\SignatureStrategy;
use SalsabilEnnaiem\PvModule\Services\SignatureService;

/**
 * simple_image : délègue au package (SignatureService). Le mécanisme
 * enregistré provient de la config pv-module.signature_mechanism
 * (= SIGNATURE_DRIVER), horodatage via signed_at.
 */
class SimpleImageSignatureStrategy implements SignatureStrategy
{
    public function __construct(private SignatureService $signatures) {}

    public function mechanism(): string
    {
        return $this->signatures->mechanism();
    }

    public function supportsSigning(mixed $actor): bool
    {
        return $this->signatures->hasSignature($actor);
    }

    public function signToken(mixed $actor): array
    {
        return [
            'mechanism' => $this->mechanism(),
            'signed_at' => now()->toIso8601String(),
        ];
    }
}
