<?php

namespace App\Imports;

use App\Enums\DossierStatut;
use App\Enums\UserRole;
use App\Models\Dossier;
use Illuminate\Validation\Rule;

/**
 * Moulinet d'import des thèses en cours (CDC §module moulinet d'import).
 * Formats : CSV, XLSX, XML, JSON. Mappage automatique + validation avant intégration.
 * Validate-then-commit : une référence (doctorant / directeur / commission) inconnue
 * invalide la ligne sans jamais être écrite.
 */
class TheseImport extends BaseImport
{
    protected function aliases(): array
    {
        return [
            'Doctorant' => 'doctorant_email',
            'Doctorant email' => 'doctorant_email',
            'Email doctorant' => 'doctorant_email',
            'Directeur' => 'directeur_email',
            'Directeur de thèse' => 'directeur_email',
            'Email directeur' => 'directeur_email',
            'Encadreur' => 'directeur_email',
            'Commission' => 'commission_nom',
            'Nom commission' => 'commission_nom',
            'Discipline' => 'commission_nom',
            'Objet' => 'objet',
            'Intitulé' => 'objet',
            'Intitule' => 'objet',
            'Sujet' => 'objet',
            'Titre' => 'objet',
            'Année' => 'annee_inscription',
            'Année d\'inscription' => 'annee_inscription',
            'Annee inscription' => 'annee_inscription',
            'Statut' => 'statut',
            'Statut dossier' => 'statut',
        ];
    }

    protected function rules(): array
    {
        return [
            'doctorant_email' => [
                'required',
                'email',
                Rule::exists('users', 'email')->where(fn ($q) => $q->where('role', UserRole::Doctorant->value)),
            ],
            'directeur_email' => [
                'nullable',
                'email',
                Rule::exists('users', 'email')->where(fn ($q) => $q->where('role', UserRole::DirecteurThese->value)),
            ],
            'commission_nom' => ['required', Rule::exists('commissions', 'nom')],
            'objet' => ['required', 'string', 'min:5', 'max:255'],
            'annee_inscription' => ['nullable', 'regex:/^\d{4}$/'],
            'statut' => ['nullable', Rule::in(collect(DossierStatut::cases())->map(fn ($s) => $s->value)->all())],
        ];
    }

    protected function createRecord(array $data): void
    {
        $directeurId = $data['directeur_email'] !== null && $data['directeur_email'] !== ''
            ? static::lookupId('users', 'email', $data['directeur_email'])
            : null;

        Dossier::create([
            'doctorant_id' => static::lookupId('users', 'email', $data['doctorant_email']),
            'directeur_id' => $directeurId,
            'commission_id' => static::lookupId('commissions', 'nom', $data['commission_nom']),
            'objet' => $data['objet'],
            'statut' => $data['statut'] ?? DossierStatut::EnCours->value,
            'annee_inscription' => $data['annee_inscription'] ?? null,
            'created_by' => auth()->id(),
        ]);
    }

    public function label(): string
    {
        return 'Thèses en cours';
    }
}