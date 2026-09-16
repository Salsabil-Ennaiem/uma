<?php

namespace App\Imports;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Import des enseignants depuis CSV / XLSX / XML / JSON (P9 §6.6 / CDC §gestion enseignants).
 * Champs mappés : nom, email, rôle, grade, structure de recherche, établissement.
 * Validate-then-commit : un email en doublon ou un rôle inconnu invalide la ligne.
 */
class EnseignantImport extends BaseImport
{
    protected function aliases(): array
    {
        return [
            'Nom' => 'name',
            'Nom complet' => 'name',
            'Prénom et nom' => 'name',
            'Email' => 'email',
            'Adresse email' => 'email',
            'Rôle' => 'role',
            'Grade' => 'grade',
            'Structure de recherche' => 'structure_recherche',
            'Structure' => 'structure_recherche',
            'Établissement' => 'etablissement',
            'Etablissement' => 'etablissement',
            'Étab' => 'etablissement',
            'Etab' => 'etablissement',
        ];
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'role' => ['required', Rule::enum(UserRole::class)],
            'grade' => ['nullable', 'string'],
            'structure_recherche' => ['nullable', 'string'],
            'etablissement' => ['nullable', Rule::exists('uma_etablissements', 'nom')],
        ];
    }

    protected function createRecord(array $data): void
    {
        User::create([
            'name' => $data['name'],
            'email' => mb_strtolower($data['email']),
            'password' => Str::random(16),
            'role' => $data['role'],
            'grade' => $data['grade'] ?? null,
            'structure_recherche' => $data['structure_recherche'] ?? null,
            'etablissement_id' => $data['etablissement'] !== null && $data['etablissement'] !== ''
                ? static::lookupId('uma_etablissements', 'nom', $data['etablissement'])
                : null,
        ]);
    }

    public function label(): string
    {
        return 'Enseignants';
    }
}