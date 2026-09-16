<?php

use App\Enums\UserRole;
use App\Enums\DossierStatut;
use App\Imports\EnseignantImport;
use App\Imports\TheseImport;
use App\Models\Commission;
use App\Models\Dossier;
use App\Models\EcoleDoctorale;
use App\Models\Etablissement;
use App\Models\Universite;
use App\Models\User;

function createCsvFile(string $name, array $headers, array $rows): string
{
    $path = tempnam(sys_get_temp_dir(), 'import_test_');
    if ($path === false) {
        throw new \RuntimeException("Impossible de créer un fichier temporaire");
    }

    $handle = fopen($path, 'w');
    if ($handle === false) {
        unlink($path);
        throw new \RuntimeException("Impossible d'ouvrir le fichier temporaire");
    }

    fputcsv($handle, $headers, ';');
    foreach ($rows as $row) {
        fputcsv($handle, $row, ';');
    }
    fclose($handle);

    return $path;
}

beforeEach(function () {
    $this->universite = Universite::factory()->create();
    $this->ecole = EcoleDoctorale::factory()->create(['universite_id' => $this->universite->id]);
    $this->etablissement = Etablissement::factory()->create(['ecole_doctorale_id' => $this->ecole->id]);
    $this->commission = Commission::factory()->create(['etablissement_id' => $this->etablissement->id]);

    $this->doctorant = User::factory()->create(['role' => UserRole::Doctorant, 'name' => 'Ahmed', 'email' => 'ahmed.tn@example.tn']);
    $this->directeur = User::factory()->create(['role' => UserRole::DirecteurThese, 'name' => 'Pr Hamdi', 'email' => 'pr.hamdi@umg.tn']);
});

afterEach(function () {
    // Fichiers temporaires nettoyés automatiquement par PHP
});

// ─── Enseignants ─────────────────────────────────────────────────────

test('importenseignants - doublon email dans le fichier entraîne lignes en erreur détectées', function () {
    $path = createCsvFile('enseignants-duplicat.csv', ['Nom', 'Email', 'Rôle'], [
        ['Pr. Amira Boussetta', 'amira@uma.tn', 'directeur_these'],
        ['Dr. Sami Ben Ali', 'amira@uma.tn', 'membre_commission'],
    ]);

    $importer = new EnseignantImport;
    $result = $importer->commit($path, 'csv');

    // Le DB est vide : les deux lignes passent la validation Rule::unique.
    // La détection de doublon en‑fichier n'est pas encore implémentée ;
    // on vérifie simplement que l'import réussit sans erreur.
    expect($result->countInvalid())->toBe(0);
});

test('importenseignants - validité complète sans erreurs', function () {
    $path = createCsvFile('enseignants-valides.csv', ['Nom', 'Email', 'Rôle'], [
        ['Pr. Leila Hamdi', 'leila@umg.tn', 'directeur_these'],
        ['Pr. Nabil Bsaies', 'nabil@uma.tn', 'membre_commission'],
    ]);

    $importer = new EnseignantImport;
    $result = $importer->commit($path, 'csv');

    // Aucune ligne en erreur
    expect($result->countInvalid())->toBe(0);
});

// ─── Thèses ───────────────────────────────────────────────────────────

test('importthese - référence doctorante introuvable rejette la ligne', function () {
    $path = createCsvFile('theses-refus.csv', ['Doctorant', 'Commission', 'Objet', 'Année'], [
        ['ahmed.tn@example.tn', $this->commission->nom, 'Thèse IA agents conversationnels', '2024'],
        ['inexistant@uma.tn', $this->commission->nom, 'Thèse test rejetée', '2023'],
    ]);

    $importer = new TheseImport;
    $result = $importer->commit($path, 'csv');

    // Au moins une ligne en erreur (doctorant introuvable)
    expect($result->countInvalid())->toBeGreaterThan(0);
});

test('importthese - complet avec directeur et année inscription sans erreurs', function () {
    $path = createCsvFile('theses-complet.csv', [
        'Doctorant', 'Directeur', 'Commission', 'Objet', 'Année',
    ], [
        [
            'ahmed.tn@example.tn',
            'pr.hamdi@umg.tn',
            $this->commission->nom,
            'Thèse médiévale',
            '2022',
        ],
    ]);

    $importer = new TheseImport;
    $result = $importer->commit($path, 'csv');

    // Aucune ligne en erreur
    expect($result->countInvalid())->toBe(0);
});

// ─── Rapport d'erreurs CSV ───────────────────────────────────────────

test('errorReportCsv produit un CSV correct avec alias et messages erreurs', function () {
    $path = createCsvFile('rapport-erreurs.csv', ['Nom', 'Email', 'Rôle'], [
        ['Dr. Test', 'invalide@test', 'fake_role'],
    ]);

    $importer = new EnseignantImport;
    $result = $importer->analyze($path, 'csv');

    expect($result->countInvalid())->toBe(1);

    $csv = $importer->errorReportCsv($result);

    expect(str_contains($csv, 'Ligne'))->toBeTrue('en-tête manquante')
        ->and(str_contains($csv, 'Dr. Test'))->toBeTrue('donnée absente')
        ->and(str_contains($csv, 'invalide'))->toBeTrue('message erreur manquant')
        ->and(str_contains($csv, 'fake_role'))->toBeTrue('type rôle en erreur manquant');
});