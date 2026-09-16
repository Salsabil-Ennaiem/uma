<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use SimpleXMLElement;

/**
 * Moteur d'import « moulinet » — P9 §6.
 *
 * Règles respectées :
 *  - validate-then-commit : aucune ligne en erreur n'est écrite en base.
 *  - tous les commits se font dans une seule transaction.
 *  - tout schéma de fichier (CSV, XLSX, XML, JSON) est accepté.
 */
abstract class BaseImport
{
    /** @var array<string, string> Alias d'en-têtes source (insensible à la casse) => champ cible. */
    abstract protected function aliases(): array;

    /** Règles de validation Laravel appliquées au champ normalisé. */
    abstract protected function rules(): array;

    /** Écrit une ligne valide en base (appelé dans la transaction). */
    abstract protected function createRecord(array $data): void;

    abstract public function label(): string;

    /**
     * Génère un rapport CSV des lignes en erreur (séparateur ';').
     */
    public function errorReportCsv(ImportResult $result): string
    {
        $handle = fopen('php://temp', 'r+');
        $labels = array_keys($this->aliases());
        fputcsv($handle, ['Ligne', ...$labels, 'Erreurs'], ';');

        foreach ($result->invalidRows() as $row) {
            $values = [];
            foreach ($this->aliases() as $field) {
                $values[] = $row->data[$field] ?? '';
            }

            fputcsv($handle, [
                $row->rowNumber,
                ...$values,
                implode(' | ', $row->errors),
            ], ';');
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }

    /**
     * Parse un fichier en un tableau de lignes associatives (clé = en-tête brut).
     *
     * @return array<int, array<string, mixed>>
     */
    public function parseFile(string $path, string $extension): array
    {
        return match (strtolower($extension)) {
            'csv', 'txt' => $this->parseCsv($path),
            'xlsx', 'xls' => $this->parseXlsx($path),
            'xml' => $this->parseXml($path),
            'json' => $this->parseJson($path),
            default => throw new \InvalidArgumentException("Format non supporté : {$extension}"),
        };
    }

    /**
     * Analyse complète : normalise puis valide chaque ligne.
     */
    public function analyze(string $path, string $extension): ImportResult
    {
        $result = new ImportResult;
        $result->rows = $this->buildRows($this->parseFile($path, $extension));

        return $result;
    }

    /**
     * Intégration validate-then-commit : analyse puis écriture transactionnelle
     * des seules lignes valides. Les lignes en erreur ne sont jamais écrites.
     */
    public function commit(string $path, string $extension): ImportResult
    {
        $result = $this->analyze($path, $extension);
        $valid = $result->validRows();

        if ($valid !== []) {
            DB::transaction(function () use ($valid, &$result): void {
                foreach ($valid as $row) {
                    try {
                        $this->createRecord($row->data);
                        $result->committed++;
                    } catch (\Throwable $e) {
                        Log::error('Import — erreur d\'intégration ligne '.$row->rowNumber, [
                            'importer' => static::class,
                            'message' => $e->getMessage(),
                        ]);
                        // La ligne échouée n'est pas validée mais n'invalide pas le lot.
                    }
                }
            });
        }

        return $result;
    }

    /**
     * Construit les lignes normalisées + validées.
     *
     * @param  array<int, array<string, mixed>>  $rawRows
     * @return array<int, ImportRow>
     */
    protected function buildRows(array $rawRows): array
    {
        $rows = [];
        $aliases = $this->normalizedAliases();

        foreach ($rawRows as $index => $raw) {
            $data = $this->normalizeRow($raw, $aliases);
            $errors = $this->validateRow($data);

            $rows[] = new ImportRow(
                rowNumber: $index + 2, // 1 = en-tête
                data: $data,
                errors: $errors,
            );
        }

        return $rows;
    }

    /**
     * @return array<string, string> Sans accent, minuscules.
     */
    protected function normalizedAliases(): array
    {
        $aliases = [];
        foreach ($this->aliases() as $label => $field) {
            $aliases[static::normalizeKey($label)] = $field;
            $aliases[static::normalizeKey($field)] = $field;
        }

        return $aliases;
    }

    /**
     * @param  array<string, mixed>  $raw
     * @param  array<string, string>  $aliases
     * @return array<string, mixed>
     */
    protected function normalizeRow(array $raw, array $aliases): array
    {
        $data = [];
        foreach ($raw as $key => $value) {
            $field = $aliases[static::normalizeKey((string) $key)] ?? null;
            if ($field === null) {
                continue;
            }

            $data[$field] = static::cleanValue($value);
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, string>
     */
    protected function validateRow(array $data): array
    {
        $validator = Validator::make($data, $this->rules());

        return $validator->errors()->all();
    }

    protected static function normalizeKey(string $key): string
    {
        $key = mb_strtolower(trim($key));
        $transliterator = \Transliterator::create('Any-Latin; Latin-ASCII');

        return $transliterator ? $transliterator->transliterate($key) : $key;
    }

    protected static function cleanValue(mixed $value): mixed
    {
        if (is_string($value)) {
            return trim($value);
        }

        return $value;
    }

    protected static function lookupId(string $table, string $field, mixed $value): ?int
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        return DB::table($table)->where($field, $value)->value('id');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Ouverture impossible : {$path}");
        }

        $lines = [];
        while (($line = fgets($handle)) !== false) {
            $lines[] = $line;
        }
        fclose($handle);

        if ($lines === []) {
            return [];
        }

        $first = ltrim($lines[0], "\xEF\xBB\xBF");
        $delimiter = substr_count($first, ';') >= substr_count($first, ',') ? ';' : ',';

        $handle = fopen('php://temp', 'r+');
        foreach ($lines as $line) {
            fwrite($handle, $line);
        }
        rewind($handle);

        $rows = [];
        $header = null;
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if ($header === null) {
                $header = array_map(fn ($h) => trim((string) $h), $row);

                continue;
            }

            $rows[] = array_combine($header, array_map(fn ($c) => (string) $c, $row));
        }
        fclose($handle);

        return array_values(array_filter($rows));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseXlsx(string $path): array
    {
        $reader = new XlsxReader;

        try {
            $reader->open($path);

            $header = null;
            $rows = [];
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $values = array_map(
                        fn ($cell) => $cell instanceof \OpenSpout\Common\Entity\Cell ? $cell->getValue() : $cell,
                        $row->getCells(),
                    );
                    $values = array_map(fn ($v) => is_scalar($v) ? (string) $v : '', $values);

                    if ($header === null) {
                        $header = array_map(fn ($h) => trim($h), $values);

                        continue;
                    }

                    $rows[] = array_combine($header, $values);
                }
            }

            return array_values(array_filter($rows));
        } finally {
            $reader->close();
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseXml(string $path): array
    {
        $xml = simplexml_load_file($path);
        if ($xml === false) {
            throw new \RuntimeException("XML illisible : {$path}");
        }

        $rows = [];
        foreach ($xml->children() as $child) {
            $row = [];
            foreach ($child->children() as $field) {
                $row[$field->getName()] = (string) $field;
            }

            if ($row !== []) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseJson(string $path): array
    {
        $decoded = json_decode((string) file_get_contents($path), true);

        if (! is_array($decoded)) {
            throw new \RuntimeException("JSON illisible : {$path}");
        }

        // Liste directe d'objets.
        if (array_is_list($decoded) && $decoded !== []) {
            return array_values($decoded);
        }

        // Objet contenant une liste (ex. {"theses": [...]}).
        foreach ($decoded as $value) {
            if (is_array($value) && array_is_list($value)) {
                return array_values($value);
            }
        }

        return [];
    }
}