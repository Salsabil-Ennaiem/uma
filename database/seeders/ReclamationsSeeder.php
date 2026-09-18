<?php

namespace Database\Seeders;

use App\Models\Commission;
use App\Models\Reclamation;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Réclamations de recette (Workflow C) : une par commission déposée
 * par un doctorant.
 *
 * Dépend de CommissionsSeeder.
 * Idempotent (déposant + objet + updateOrCreate).
 */
class ReclamationsSeeder extends Seeder
{
    public function run(): void
    {
        $commissionInformatique = Commission::where('nom', 'Commission Sciences de l\'Informatique')->firstOrFail();
        $commissionGestion = Commission::where('nom', 'Commission Sciences de Gestion')->firstOrFail();

        $this->reclamation(
            $this->user('doctorant3@uma.tn'),
            $commissionGestion,
            'changement_titre',
            'Changement de l\'intitulé de la thèse',
            'L\'intitulé actuel ne reflète plus la problématique finalement traitée.',
            'prioritaire',
        );
        $this->reclamation(
            $this->user('doctorant5@uma.tn'),
            $commissionInformatique,
            'cotutelle',
            'Demande de cotutelle internationale',
            'Mise en place d\'une cotutelle avec une université partenaire dans le cadre du plan national.',
            'tres_urgente',
        );
    }

    protected function user(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    protected function reclamation(
        User $deposant,
        Commission $commission,
        string $type,
        string $objet,
        string $contenu,
        string $urgence,
    ): Reclamation {
        return Reclamation::updateOrCreate(
            ['user_id' => $deposant->id, 'objet' => $objet],
            [
                'commission_id' => $commission->id,
                'type' => $type,
                'contenu' => $contenu,
                'urgence' => $urgence,
                'statut' => 'ouverte',
                'created_by' => $deposant->id,
            ],
        );
    }
}
