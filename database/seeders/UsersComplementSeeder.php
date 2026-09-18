<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Etablissement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Comptes de recette complémentaires + rattachements (grade, structure,
 * établissement) des comptes de base. Mot de passe commun « password »
 * (recette locale uniquement — cf. ASSUMPTIONS H08).
 *
 * Dépend de InstitutionSeeder pour les établissements et de UsersSeeder
 * pour les comptes de base. Idempotent (email + updateOrCreate).
 */
class UsersComplementSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $ensi = $this->etablissement("École Nationale des Sciences de l'Informatique (ENSI)");
        $isamm = $this->etablissement('Institut Supérieur des Arts Multimédias (ISAMM)');
        $fst = $this->etablissement('Faculté des Sciences de Tunis (FST)');
        $fsjeg = $this->etablissement('Faculté des Sciences Juridiques, Économiques et de Gestion (FSJEG)');

        $this->rattacher($ensi->id, 'gestionnaire@uma.tn');
        $this->rattacher($ensi->id, 'doctorant@uma.tn');

        $this->user('president2@uma.tn', 'Présidente de Commission 2', UserRole::PresidentCommission, $password, 'Pr', 'Laboratoire LIA', $fsjeg);
        $this->user('directeur1@uma.tn', 'Directeur de Thèse 2', UserRole::DirecteurThese, $password, 'MCF', 'Laboratoire CRISTAL', $ensi);
        $this->user('directeur2@uma.tn', 'Directrice de Thèse 3', UserRole::DirecteurThese, $password, 'MCA', 'Laboratoire RECODEV', $isamm);
        $this->user('membre1@uma.tn', 'Membre de Commission 2', UserRole::MembreCommission, $password, 'MAB', 'Laboratoire CRISTAL', $ensi);
        $this->user('membre2@uma.tn', 'Membre de Commission 3', UserRole::MembreCommission, $password, 'MCG', 'Laboratoire LIA', $fsjeg);
        $this->user('membre3@uma.tn', 'Membre de Commission 4', UserRole::MembreCommission, $password, 'MAB', 'Laboratoire RECODEV', $isamm);

        $this->user('doctorant2@uma.tn', 'Doctorant 2', UserRole::Doctorant, $password, null, null, $ensi);
        $this->user('doctorant3@uma.tn', 'Doctorant 3', UserRole::Doctorant, $password, null, null, $isamm);
        $this->user('doctorant4@uma.tn', 'Doctorant 4', UserRole::Doctorant, $password, null, null, $fst);
        $this->user('doctorant5@uma.tn', 'Doctorant 5', UserRole::Doctorant, $password, null, null, $fsjeg);
    }

    protected function etablissement(string $nom): Etablissement
    {
        return Etablissement::where('nom', $nom)->firstOrFail();
    }

    protected function rattacher(int $etablissementId, string $email): void
    {
        User::where('email', $email)->update(['etablissement_id' => $etablissementId]);
    }

    protected function user(
        string $email,
        string $name,
        UserRole $role,
        string $password,
        ?string $grade = null,
        ?string $structure = null,
        ?Etablissement $etablissement = null,
    ): User {
        return User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'role' => $role,
                'password' => $password,
                'email_verified_at' => now(),
                'grade' => $grade,
                'structure_recherche' => $structure,
                'etablissement_id' => $etablissement?->id,
            ],
        );
    }
}
