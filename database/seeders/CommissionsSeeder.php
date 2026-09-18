<?php

namespace Database\Seeders;

use App\Models\Commission;
use App\Models\Etablissement;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Commissions de recette : une par discipline, avec président dédié
 * et membres rattachés via le pivot.
 *
 * Dépend de UsersSeeder, UsersComplementSeeder et InstitutionSeeder.
 * Idempotent (nom + updateOrCreate).
 */
class CommissionsSeeder extends Seeder
{
    public function run(): void
    {
        $president = $this->user('president@uma.tn');
        $president2 = $this->user('president2@uma.tn');
        $membre = $this->user('membre@uma.tn');
        $membre1 = $this->user('membre1@uma.tn');
        $membre2 = $this->user('membre2@uma.tn');
        $membre3 = $this->user('membre3@uma.tn');

        $ensi = $this->etablissement("École Nationale des Sciences de l'Informatique (ENSI)");
        $fsjeg = $this->etablissement('Faculté des Sciences Juridiques, Économiques et de Gestion (FSJEG)');

        $commissionInformatique = Commission::updateOrCreate(
            ['nom' => 'Commission Sciences de l\'Informatique'],
            [
                'etablissement_id' => $ensi->id,
                'president_id' => $president->id,
                'discipline' => 'Sciences de l\'informatique',
                'is_active' => true,
            ],
        );
        $commissionGestion = Commission::updateOrCreate(
            ['nom' => 'Commission Sciences de Gestion'],
            [
                'etablissement_id' => $fsjeg->id,
                'president_id' => $president2->id,
                'discipline' => 'Sciences de gestion',
                'is_active' => true,
            ],
        );

        $this->attacherMembres($commissionInformatique, [$membre, $membre1, $membre2]);
        $this->attacherMembres($commissionGestion, [$membre1, $membre3]);
    }

    protected function user(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    protected function etablissement(string $nom): Etablissement
    {
        return Etablissement::where('nom', $nom)->firstOrFail();
    }

    protected function attacherMembres(Commission $commission, array $users): void
    {
        foreach ($users as $user) {
            if (! $commission->estMembre($user)) {
                $commission->membres()->attach($user->id);
            }
        }
    }
}
