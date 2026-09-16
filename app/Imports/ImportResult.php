<?php

namespace App\Imports;

/**
 * Résultat d'une analyse / intégration d'import.
 * Conforme validate-then-commit : on ne commit jamais de ligne en erreur.
 */
class ImportResult
{
    /** @var array<int, ImportRow> */
    public array $rows = [];

    public int $committed = 0;

    /**
     * @return array<int, ImportRow>
     */
    public function validRows(): array
    {
        return array_values(array_filter(
            $this->rows,
            fn (ImportRow $row) => $row->isValid(),
        ));
    }

    /**
     * @return array<int, ImportRow>
     */
    public function invalidRows(): array
    {
        return array_values(array_filter(
            $this->rows,
            fn (ImportRow $row) => ! $row->isValid(),
        ));
    }

    public function countValid(): int
    {
        return count($this->validRows());
    }

    public function countInvalid(): int
    {
        return count($this->invalidRows());
    }
}