<?php

namespace Database\Seeders;

use App\Enums\ReunionStatut;
use App\Enums\ReunionType;
use App\Models\Commission;
use App\Models\Dossier;
use App\Models\OdjTemplate;
use App\Models\Reunion;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Réunions de recette : un ordre du jour standard par commission et
 * des réunions planifiées auxquelles des dossiers sont inscrits.
 *
 * Dépend de CommissionsSeeder et DossiersSeeder.
 * Idempotent (commission + objet + updateOrCreate).
 */
class ReunionsSeeder extends Seeder
{
    public function run(): void
    {
        $administrateur = $this->user('admin@uma.tn');
        $gestionnaire = $this->user('gestionnaire@uma.tn');

        $commissionInformatique = Commission::where('nom', 'Commission Sciences de l\'Informatique')->firstOrFail();
        $commissionGestion = Commission::where('nom', 'Commission Sciences de Gestion')->firstOrFail();

        $odjInfo = $this->odjTemplate($commissionInformatique, $administrateur);
        $odjGestion = $this->odjTemplate($commissionGestion, $administrateur);

        $reunionInfo = $this->reunion(
            $commissionInformatique,
            $odjInfo,
            $gestionnaire,
            'Commission scientifique — réunion mensuelle',
            'Examen des dossiers d\'inscription et de réinscription.',
            '+7 days',
            ReunionType::Presentiel,
            'Salle des conseils — ENSI',
        );
        $this->inscrireDossiers($reunionInfo, $this->positions([
            [$this->dossier('doctorant@uma.tn', 'Inscription en 2ème année — renouvellement'), 1],
            [$this->dossier('doctorant2@uma.tn', 'Demande d\'inscription en 1ère année'), 2],
            [$this->dossier('doctorant5@uma.tn', 'Préparation soutenance — 5ème année'), 3],
        ]));

        $reunionGestion = $this->reunion(
            $commissionGestion,
            $odjGestion,
            $gestionnaire,
            'Délibérations — rentrée scientifique',
            'Délibérations sur les dossiers de réinscription et réclamations.',
            '+14 days',
            ReunionType::Visio,
            'Visioconférence — lien transmis par invitation',
        );
        $this->inscrireDossiers($reunionGestion, $this->positions([
            [$this->dossier('doctorant3@uma.tn', 'Inscription 3ème année — validation dossiers'), 1],
            [$this->dossier('doctorant4@uma.tn', 'Inscription 4ème année — dossier en cours'), 2],
        ]));
    }

    protected function user(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    protected function dossier(string $emailDoctorant, string $objet): Dossier
    {
        return Dossier::where('doctorant_id', $this->user($emailDoctorant)->id)
            ->where('objet', $objet)
            ->firstOrFail();
    }

    protected function positions(array $items): array
    {
        $positions = [];

        foreach ($items as [$dossier, $position]) {
            $positions[$dossier->id] = ['position' => $position];
        }

        return $positions;
    }

    protected function inscrireDossiers(Reunion $reunion, array $positions): void
    {
        $dejaInscrits = $reunion->dossiers()
            ->pluck((new Dossier)->qualifyColumn('id'))
            ->flip()
            ->all();

        $nouveaux = array_diff_key($positions, $dejaInscrits);

        if ($nouveaux !== []) {
            $reunion->dossiers()->attach($nouveaux);
        }
    }

    protected function odjTemplate(Commission $commission, User $creator): OdjTemplate
    {
        return OdjTemplate::updateOrCreate(
            ['commission_id' => $commission->id, 'label' => 'Ordre du jour standard'],
            [
                'description' => 'Modèle d\'ordre du jour par défaut pour les réunions de la commission.',
                'contenu' => "1. Ouverture de séance et appel\n2. Examen des dossiers inscrits à l'ordre du jour\n3. Délibérations et propositions de décisions\n4. Divers\n5. Clôture de la séance",
                'is_active' => true,
                'created_by' => $creator->id,
            ],
        );
    }

    protected function reunion(
        Commission $commission,
        OdjTemplate $template,
        User $creator,
        string $objet,
        string $description,
        string $dateDebut,
        ReunionType $type,
        string $lieu,
        ?string $lien = null,
    ): Reunion {
        return Reunion::updateOrCreate(
            ['commission_id' => $commission->id, 'objet' => $objet],
            [
                'description' => $description,
                'odj_template_id' => $template->id,
                'date_debut' => now()->add($dateDebut),
                'type' => $type,
                'statut' => ReunionStatut::Planifiee,
                'lieu' => $lieu,
                'lien' => $lien,
                'created_by' => $creator->id,
            ],
        );
    }
}
