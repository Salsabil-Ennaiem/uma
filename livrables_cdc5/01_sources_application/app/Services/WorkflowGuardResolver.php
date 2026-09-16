<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\User;
use App\Models\WorkflowGuard;
use App\Models\WorkflowInstance;
use Carbon\CarbonInterface;
use DateTimeInterface;
use SalsabilEnnaiem\PvModule\Contracts\CanManagePv;

/**
 * Évaluation des gardes paramétrables d'un workflow (P8).
 *
 * Règles prises en charge (colonne `rule` de workflow_guards) :
 *  - `data`        : condition sur les données de l'instance. Paramètres :
 *                    `{ key, operator, value }` (==, !=, >=, >, <=, <, in).
 *  - `document`    : le sujet possède un document du type métier attendu.
 *                    Paramètres : `{ type }`.
 *  - `contract`    : réutilise un contrat du package (P5) — ne contourne jamais
 *                    les Rules. Paramètres : `{ method, args: [...] }`.
 *  - `no_overlap`  : aucune réservation jury/salle en conflit sur la plage du
 *                    payload. Paramètres : `{ type: 'salle'|'jury' }`.
 */
class WorkflowGuardResolver
{
    public function evaluate(WorkflowGuard $guard, WorkflowInstance $instance, ?User $actor = null, array $payload = []): array
    {
        $params = (array) $guard->params;

        $allowed = match ($guard->rule) {
            'data' => $this->checkData($instance, $params, $payload),
            'document' => $this->checkDocument($instance, $params),
            'contract' => $this->checkContract($params, $actor),
            'no_overlap' => $this->checkNoOverlap($instance, $params, $payload),
            default => $this->unknownRule($guard),
        };

        return [
            'allowed' => $allowed,
            'message' => $guard->error_message ?? null,
        ];
    }

    protected function checkData(WorkflowInstance $instance, array $params, array $payload = []): bool
    {
        $key = $params['key'] ?? null;
        $value = $params['value'] ?? null;
        $operator = $params['operator'] ?? '==';
        $all = array_merge((array) $instance->data, $payload);
        $actual = data_get($all, $key);

        if ($operator === 'in') {
            return in_array($actual, (array) $value, true);
        }

        return match ($operator) {
            '==' => $actual == $value,
            '!=' => $actual != $value,
            '>=' => $actual >= $value,
            '>' => $actual > $value,
            '<=' => $actual <= $value,
            '<' => $actual < $value,
            default => false,
        };
    }

    protected function checkDocument(WorkflowInstance $instance, array $params): bool
    {
        $type = $params['type'] ?? null;
        $subject = $instance->subject;

        if ($type === null || $subject === null) {
            return false;
        }

        return method_exists($subject, 'documents')
            && $subject->documents()->where('type', $type)->exists();
    }

    protected function checkContract(array $params, ?User $actor): bool
    {
        $contract = $params['contract'] ?? null;
        $method = $params['method'] ?? null;

        if ($contract === null || $method === null || $actor === null) {
            return false;
        }

        $instance = app($contract);

        // Cas particulier : contract-only des Rules du package.
        if ($contract === CanManagePv::class) {
            return $instance->{$method}($actor);
        }

        return $instance->{$method}($actor);
    }

    protected function checkNoOverlap(WorkflowInstance $instance, array $params, array $payload = []): bool
    {
        $type = $params['type'] ?? Reservation::TYPE_SALLE;
        $all = array_merge((array) $instance->data, $payload);

        $debut = $all['soutenance_date'] ?? null;
        $fin = $all['soutenance_fin'] ?? null;

        if (! $this->parseDate($debut) || ! $this->parseDate($fin)) {
            return false;
        }

        $salle = $all['salle'] ?? null;
        $jurys = (array) ($all['jury_membres'] ?? []);

        // Chevauchement de salle.
        if ($type === Reservation::TYPE_SALLE) {
            return ! Reservation::query()
                ->chevauche(Reservation::TYPE_SALLE, $salle, null, $debut, $fin)
                ->exists();
        }

        // Chevauchement de membre de jury.
        foreach ($jurys as $membreId) {
            if (Reservation::query()
                ->chevauche(Reservation::TYPE_JURY, null, (int) $membreId, $debut, $fin)
                ->exists()) {
                return false;
            }
        }

        return true;
    }

    protected function unknownRule(WorkflowGuard $guard): bool
    {
        return in_array($guard->rule, ['role'], true);
    }

    protected function parseDate(mixed $value): ?CarbonInterface
    {
        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        if (is_string($value) && filled($value)) {
            return \Illuminate\Support\Carbon::parse($value);
        }

        return null;
    }
}