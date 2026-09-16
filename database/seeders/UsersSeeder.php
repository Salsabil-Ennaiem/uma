<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Jeu de comptes de recette : un utilisateur par rôle, mot de passe commun
 * « password » (recette locale uniquement — en production, procédure auditée
 * cf. ASSUMPTIONS H08).
 */
class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $users = [
            ['email' => 'admin@uma.tn', 'name' => 'Administrateur UMA', 'role' => UserRole::Admin],
            ['email' => 'gestionnaire@uma.tn', 'name' => 'Gestionnaire École', 'role' => UserRole::GestionnaireEcole],
            ['email' => 'agent@uma.tn', 'name' => "Agent d'Administration", 'role' => UserRole::AgentAdministration],
            ['email' => 'president@uma.tn', 'name' => 'Président de Commission', 'role' => UserRole::PresidentCommission],
            ['email' => 'membre@uma.tn', 'name' => 'Membre de Commission', 'role' => UserRole::MembreCommission],
            ['email' => 'directeur@uma.tn', 'name' => 'Directeur de Thèse', 'role' => UserRole::DirecteurThese],
            ['email' => 'doctorant@uma.tn', 'name' => 'Doctorant 1', 'role' => UserRole::Doctorant],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => $password,
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
