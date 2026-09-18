<?php

namespace Database\Seeders;

use App\Enums\DossierStatut;
use App\Models\Commission;
use App\Models\Dossier;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Dossiers de recette répartis entre les commissions de doctorat
 * (statuts et années d'inscription variés : 1ʳᵉ → 5ᵉ).
 *
 * Dépend de UsersSeeder, UsersComplementSeeder et CommissionsSeeder.
 * Idempotent (doctorant + objet + updateOrCreate).
 */
class DossiersSeeder extends Seeder
{
    public function run(): void
    {
        $gestionnaire = $this->user('gestionnaire@uma.tn');
        $directeur = $this->user('directeur@uma.tn');
        $directeur1 = $this->user('directeur1@uma.tn');
        $directeur2 = $this->user('directeur2@uma.tn');

        $commissionInformatique = Commission::where('nom', 'Commission Sciences de l\'Informatique')->firstOrFail();
        $commissionGestion = Commission::where('nom', 'Commission Sciences de Gestion')->firstOrFail();

        $this->dossier($this->user('doctorant@uma.tn'), $commissionInformatique, $directeur, $gestionnaire, 'Inscription en 2ème année — renouvellement', 'Dossier de réinscription pour l\'année universitaire en cours.', DossierStatut::EnCours, '2eme');
        $this->dossier($this->user('doctorant2@uma.tn'), $commissionInformatique, $directeur1, $gestionnaire, 'Demande d\'inscription en 1ère année', 'Première inscription au doctorat, mémoire de master soutenu.', DossierStatut::EnAttente, '1ere');
        $this->dossier($this->user('doctorant3@uma.tn'), $commissionGestion, $directeur2, $gestionnaire, 'Inscription 3ème année — validation dossiers', 'Dossier complet : attestations, rapports, inscription à jour.', DossierStatut::EnAttente, '3eme');
        $this->dossier($this->user('doctorant4@uma.tn'), $commissionGestion, $directeur1, $gestionnaire, 'Inscription 4ème année — dossier en cours', 'Rapport d\'avancement soumis, vérification des pièces.', DossierStatut::EnCours, '4eme');
        $this->dossier($this->user('doctorant5@uma.tn'), $commissionInformatique, $directeur, $gestionnaire, 'Préparation soutenance — 5ème année', 'Dossier de pré-soutenance : rapports de progression et demandes.', DossierStatut::Traite, '5eme');
    }

    protected function user(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    protected function dossier(
        User $doctorant,
        Commission $commission,
        User $directeur,
        User $gestionnaire,
        string $objet,
        string $description,
        DossierStatut $statut,
        string $annee,
    ): Dossier {
        return Dossier::updateOrCreate(
            ['doctorant_id' => $doctorant->id, 'objet' => $objet],
            [
                'directeur_id' => $directeur->id,
                'commission_id' => $commission->id,
                'description' => $description,
                'statut' => $statut,
                'annee_inscription' => $annee,
                'created_by' => $gestionnaire->id,
            ],
        );
    }
}
