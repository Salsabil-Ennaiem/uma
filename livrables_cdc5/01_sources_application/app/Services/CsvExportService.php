<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Export CSV maison — conforme aux règles d'économie P9.
 * Séparateur ';' par défaut pour compatibilité Excel FR.
 */
class CsvExportService
{
    public static function generate(
        Collection $records,
        array $columns,
        string $delimiter = ';',
    ): string {
        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, array_keys($columns), $delimiter);

        foreach ($records as $record) {
            $row = collect($columns)->mapWithKeys(
                fn (string $field, string $label) => [$label => static::resolveField($record, $field)],
            )->values()->all();
            fputcsv($handle, $row, $delimiter);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }

    public static function download(
        Collection $records,
        array $columns,
        string $fileName = 'export.csv',
        string $delimiter = ';',
    ) {
        $csv = static::generate($records, $columns, $delimiter);

        return response()->streamDownload(
            fn () => print $csv,
            $fileName,
            ['Content-Type' => 'text/csv; charset=UTF-8'],
        );
    }

    private static function resolveField($record, string $field)
    {
        $data = $record->toArray();

        return Arr::get($data, $field, '');
    }
}
