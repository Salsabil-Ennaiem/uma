<?php

namespace App\Contracts;

/**
 * Stratégie de signature (R2) : le mécanisme et le droit de signer sont
 * décidés à un seul endroit (SignatureResolver <- config SIGNATURE_DRIVER).
 * Chaque stratégie retourne toujours le mécanisme + un horodatage (trace P2).
 */
interface SignatureStrategy
{
    public function mechanism(): string;

    public function supportsSigning(mixed $actor): bool;

    /**
     * @return array{mechanism: string, signed_at: string}
     */
    public function signToken(mixed $actor): array;
}
