<?php

namespace App\Imports;

/** Représente une ligne du fichier sourcé après normalisation. */
class ImportRow
{
    public function __construct(
        public readonly int $rowNumber,
        public readonly array $data,
        public readonly array $errors = [],
    ) {}

    public function isValid(): bool
    {
        return $this->errors === [];
    }
}