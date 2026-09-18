<?php

namespace Database\Seeders;

use App\Models\Dossier;
use App\Models\Reclamation;
use App\Models\WorkflowDefinition;
use App\Models\WorkflowGuard;
use App\Models\WorkflowTransition;
use Illuminate\Database\Seeder;
use SalsabilEnnaiem\PvModule\Contracts\CanManagePv;

/**
 * Déclarations en base des workflows métier du CDC (P8) :
 *  - A : inscriptions 1ʳᵉ → 5ᵉ année (variations par niveau) ;
 *  - B : soutenance (dépôt → rapporteurs → jury → planification → diplôme) ;
 *  - C : réclamations / ticketing (états, priorités, discussions, clôture).
 *
 * Transitions, rôles, gardes et actions sont des lignes de base : aucune
 * transition n'est déclarée en dur dans l'application.
 */
class WorkflowSeeder extends Seeder
{
    public function run(): void
    {
        $this->workflowA();
        $this->workflowA2();
        $this->workflowB();
        $this->workflowC();
    }

    protected function workflowA(): void
    {
        $def = WorkflowDefinition::firstOrCreate(
            ['code' => 'inscription'],
            [
                'name' => 'Inscription en doctorat (1ʳᵉ année)',
                'description' => 'Dépôt → validation dossier → paiement inscription.tn → reçu → attestation FR/AR → archivage.',
                'subject_type' => Dossier::class,
                'states' => [
                    'demande',
                    'valide_agent',
                    'paiement_attendu',
                    'recu_uploaded',
                    'recu_valide',
                    'attestation_generee',
                    'archivee',
                ],
                'initial_state' => 'demande',
                'is_active' => true,
            ],
        );

        $this->transition($def, 'demande', 'valide_agent', 'valider_agent', [
            'roles' => ['agent_administration', 'gestionnaire_ecole'],
            'sort' => 10,
        ]);

        $this->transition($def, 'valide_agent', 'paiement_attendu', 'demander_paiement', [
            'roles' => ['gestionnaire_ecole'],
            'actions' => [],
            'notifications' => [[
                'role' => 'doctorant',
                'message' => 'Votre dossier est accepté. Effectuez votre paiement sur inscription.tn puis déposez le reçu.',
            ]],
            'sort' => 20,
        ]);

        $this->transition($def, 'paiement_attendu', 'recu_uploaded', 'deposer_recu', [
            'roles' => ['doctorant'],
            'sort' => 30,
        ]);

        $this->transition($def, 'recu_uploaded', 'recu_valide', 'valider_recu', [
            'roles' => ['agent_administration', 'gestionnaire_ecole'],
            'guards' => [[
                'rule' => 'data',
                'params' => ['key' => 'reception_paiement', 'operator' => '==', 'value' => true],
                'error_message' => 'Le reçu de paiement n\'a pas été vérifié.',
            ]],
            'sort' => 40,
        ]);

        $this->transition($def, 'recu_valide', 'attestation_generee', 'generer_attestation', [
            'roles' => ['agent_administration', 'gestionnaire_ecole', 'admin'],
            'actions' => ['attestation.generer'],
            'notifications' => [[
                'role' => 'doctorant',
                'message' => 'Votre attestation d\'inscription sera prête dans 72 heures.',
            ]],
            'guards' => [[
                'rule' => 'contract',
                'params' => ['contract' => CanManagePv::class, 'method' => 'canCreate'],
                'error_message' => 'Le contrat du package refuse la génération.',
            ]],
            'sort' => 50,
        ]);

        $this->transition($def, 'attestation_generee', 'archivee', 'archiver', [
            'roles' => ['admin'],
            'sort' => 60,
        ]);
    }

    /**
     * Ré-inscription 2ᵉ → 5ᵉ année : après validation, le dossier est examiné
     * par la commission (ODJ + PV) ; au-delà d'un niveau seuil, la dérogation
     * du président d'université est requise avant le paiement (CDC §3).
     */
    protected function workflowA2(): void
    {
        $def = WorkflowDefinition::firstOrCreate(
            ['code' => 'reinscription'],
            [
                'name' => 'Ré-inscription en doctorat (2ᵉ → 5ᵉ année)',
                'description' => 'Dépôt → validation → rapport d\'avancement → examen commission (PV) → dérogation éventuelle → paiement → reçu → attestation FR/AR → archivage.',
                'subject_type' => Dossier::class,
                'states' => [
                    'demande',
                    'valide_agent',
                    'commission_examen',
                    'pv_valide',
                    'derogation_requise',
                    'derogation_accorde',
                    'paiement_attendu',
                    'recu_uploaded',
                    'recu_valide',
                    'attestation_generee',
                    'archivee',
                ],
                'initial_state' => 'demande',
                'is_active' => true,
            ],
        );

        $this->transition($def, 'demande', 'valide_agent', 'valider_agent', [
            'roles' => ['agent_administration', 'gestionnaire_ecole'],
            'sort' => 10,
        ]);

        // Passage en commission : le rapport d'avancement du doctorant est requis.
        $this->transition($def, 'valide_agent', 'commission_examen', 'inscrire_commission', [
            'roles' => ['gestionnaire_ecole', 'president_commission'],
            'guards' => [[
                'rule' => 'document',
                'params' => ['type' => 'rapport_avancement'],
                'error_message' => 'Le rapport d\'avancement du doctorant est manquant.',
            ]],
            'sort' => 20,
        ]);

        // Décision de la commission matérialisée par un PV.
        $this->transition($def, 'commission_examen', 'pv_valide', 'valider_pv_commission', [
            'roles' => ['president_commission'],
            'guards' => [[
                'rule' => 'document',
                'params' => ['type' => 'pv'],
                'error_message' => 'Le procès-verbal de la commission est manquant.',
            ]],
            'sort' => 30,
        ]);

        // Branche principale : niveau < 4 → poursuite directe vers le paiement.
        $this->transition($def, 'pv_valide', 'paiement_attendu', 'suite_inscription', [
            'roles' => ['gestionnaire_ecole'],
            'guards' => [[
                'rule' => 'data',
                'params' => ['key' => 'niveau', 'operator' => '<', 'value' => 4],
                'error_message' => 'Ce niveau ne requiert pas de dérogation.',
            ]],
            'sort' => 40,
        ]);

        // Branche dérogation : niveau ≥ 4 → décision du président d'université.
        $this->transition($def, 'pv_valide', 'derogation_requise', 'demander_derogation', [
            'roles' => ['gestionnaire_ecole'],
            'guards' => [[
                'rule' => 'data',
                'params' => ['key' => 'niveau', 'operator' => '>=', 'value' => 4],
                'error_message' => 'La dérogation du président d\'université est requise pour ce niveau.',
            ]],
            'sort' => 41,
        ]);

        $this->transition($def, 'derogation_requise', 'derogation_accorde', 'approuver_derogation', [
            'roles' => ['admin'],
            'notifications' => [[
                'role' => 'doctorant',
                'message' => 'Votre dérogation de prolongation a été approuvée.',
            ]],
            'sort' => 42,
        ]);

        $this->transition($def, 'derogation_accorde', 'paiement_attendu', 'suite_paiement_derogation', [
            'roles' => ['gestionnaire_ecole'],
            'sort' => 43,
        ]);

        $this->transition($def, 'paiement_attendu', 'recu_uploaded', 'deposer_recu', [
            'roles' => ['doctorant'],
            'sort' => 50,
        ]);

        $this->transition($def, 'recu_uploaded', 'recu_valide', 'valider_recu', [
            'roles' => ['agent_administration', 'gestionnaire_ecole'],
            'guards' => [[
                'rule' => 'data',
                'params' => ['key' => 'reception_paiement', 'operator' => '==', 'value' => true],
                'error_message' => 'Le reçu de paiement n\'a pas été vérifié.',
            ]],
            'sort' => 60,
        ]);

        $this->transition($def, 'recu_valide', 'attestation_generee', 'generer_attestation', [
            'roles' => ['agent_administration', 'gestionnaire_ecole', 'admin'],
            'actions' => ['attestation.generer'],
            'notifications' => [[
                'role' => 'doctorant',
                'message' => 'Votre attestation d\'inscription sera prête dans 72 heures.',
            ]],
            'sort' => 70,
        ]);

        $this->transition($def, 'attestation_generee', 'archivee', 'archiver', [
            'roles' => ['admin'],
            'sort' => 80,
        ]);
    }

    protected function workflowB(): void
    {
        $def = WorkflowDefinition::firstOrCreate(
            ['code' => 'soutenance'],
            [
                'name' => 'Soutenance de thèse',
                'description' => 'Conditions d\'éligibilité → dépôt → rapporteurs → jury → planification (anti-chevauchement) → soutenance → diplôme MESRS.',
                'subject_type' => Dossier::class,
                'states' => [
                    'depot',
                    'depot_valide',
                    'rapporteurs_designation',
                    'rapports_recus',
                    'jury_designation',
                    'rectorat_dossier',
                    'arrete_jury',
                    'planification',
                    'convocations',
                    'soutenance',
                    'diplome',
                    'diplome_disponible',
                ],
                'initial_state' => 'depot',
                'is_active' => true,
            ],
        );

        // Éligibilité (≥ 3 inscriptions + crédits validés + rapport d'approbation + autorisation directeur).
        $this->transition($def, 'depot', 'depot_valide', 'valider_depot', [
            'roles' => ['agent_administration', 'gestionnaire_ecole'],
            'guards' => [
                ['rule' => 'data', 'params' => ['key' => 'inscriptions_count', 'operator' => '>=', 'value' => 3], 'error_message' => 'Au minimum trois inscriptions sont requises (JORT).'],
                ['rule' => 'data', 'params' => ['key' => 'credits_valides', 'operator' => '==', 'value' => true], 'error_message' => 'La capitalisation des crédits n\'est pas validée.'],
                ['rule' => 'document', 'params' => ['type' => 'rapport_approbation'], 'error_message' => 'Le rapport d\'approbation de l\'encadreur est manquant.'],
            ],
            'sort' => 10,
        ]);

        $this->transition($def, 'depot_valide', 'rapporteurs_designation', 'designer_rapporteurs', [
            'roles' => ['president_commission', 'gestionnaire_ecole'],
            'sort' => 20,
        ]);

        // Deux avis favorables des rapporteurs requis.
        $this->transition($def, 'rapporteurs_designation', 'rapports_recus', 'enregistrer_rapports', [
            'roles' => ['agent_administration', 'gestionnaire_ecole', 'president_commission'],
            'guards' => [[
                'rule' => 'data',
                'params' => ['key' => 'rapporteurs_favorables', 'operator' => '>=', 'value' => 2],
                'error_message' => 'Deux rapports favorables sont requis.',
            ]],
            'sort' => 30,
        ]);

        $this->transition($def, 'rapports_recus', 'jury_designation', 'designer_jury', [
            'roles' => ['president_commission', 'gestionnaire_ecole'],
            'sort' => 40,
        ]);

        $this->transition($def, 'jury_designation', 'rectorat_dossier', 'envoyer_rectorat', [
            'roles' => ['agent_administration', 'gestionnaire_ecole'],
            'sort' => 50,
        ]);

        // Arrêté de composition de jury validé par le président d'université.
        $this->transition($def, 'rectorat_dossier', 'arrete_jury', 'valider_arrete', [
            'roles' => ['admin'],
            'guards' => [[
                'rule' => 'document',
                'params' => ['type' => 'arrete'],
                'error_message' => 'L\'arrêté de composition de jury est manquant.',
            ]],
            'sort' => 60,
        ]);

        // Planification : contrôle de chevauchement jury/salles (CDC §10).
        $this->transition($def, 'arrete_jury', 'planification', 'planifier_soutenance', [
            'roles' => ['admin', 'gestionnaire_ecole'],
            'actions' => ['reservation.creer'],
            'guards' => [
                ['rule' => 'no_overlap', 'params' => ['type' => 'salle'], 'error_message' => 'La salle est déjà réservée sur cette plage horaire.'],
                ['rule' => 'no_overlap', 'params' => ['type' => 'jury'], 'error_message' => 'Un membre du jury est déjà retenu sur cette plage horaire.'],
            ],
            'sort' => 70,
        ]);

        $this->transition($def, 'planification', 'convocations', 'envoyer_convocations', [
            'roles' => ['admin', 'gestionnaire_ecole', 'agent_administration'],
            'notifications' => [[
                'role' => 'doctorant',
                'message' => 'Votre soutenance est planifiée. Les convocations sont envoyées.',
            ]],
            'sort' => 80,
        ]);

        $this->transition($def, 'convocations', 'soutenance', 'cloturer_soutenance', [
            'roles' => ['admin', 'president_commission', 'agent_administration'],
            'sort' => 90,
        ]);

        $this->transition($def, 'soutenance', 'diplome', 'editer_diplome', [
            'roles' => ['admin', 'agent_administration'],
            'sort' => 100,
        ]);

        $this->transition($def, 'diplome', 'diplome_disponible', 'notifier_diplome', [
            'roles' => ['agent_administration'],
            'notifications' => [[
                'role' => 'doctorant',
                'message' => 'Votre diplôme (MESRS) est disponible.',
            ]],
            'sort' => 110,
        ]);
    }

    protected function workflowC(): void
    {
        $def = WorkflowDefinition::firstOrCreate(
            ['code' => 'reclamation'],
            [
                'name' => 'Réclamation / ticketing',
                'description' => 'Dépôt d\'une réclamation (priorités paramétrables), traitement, discussions, clôture.',
                'subject_type' => Reclamation::class,
                'states' => [
                    'ouverte',
                    'en_cours',
                    'en_attente_direction',
                    'traitee',
                    'cloturee',
                ],
                'initial_state' => 'ouverte',
                'is_active' => true,
            ],
        );

        $this->transition($def, 'ouverte', 'en_cours', 'prendre_en_charge', [
            'roles' => ['agent_administration', 'gestionnaire_ecole', 'president_commission'],
            'actions' => ['reclamation.actualiser'],
            'sort' => 10,
        ]);

        $this->transition($def, 'en_cours', 'en_attente_direction', 'transmettre_direction', [
            'roles' => ['agent_administration', 'gestionnaire_ecole'],
            'actions' => ['reclamation.actualiser'],
            'sort' => 20,
        ]);

        $this->transition($def, 'en_attente_direction', 'traitee', 'valider_direction', [
            'roles' => ['admin', 'gestionnaire_ecole'],
            'actions' => ['reclamation.actualiser'],
            'sort' => 30,
        ]);

        $this->transition($def, 'traitee', 'cloturee', 'cloturer', [
            'roles' => ['admin', 'agent_administration', 'gestionnaire_ecole'],
            'actions' => ['reclamation.actualiser'],
            'notifications' => [[
                'role' => 'doctorant',
                'message' => 'Votre réclamation a été clôturée.',
            ]],
            'sort' => 40,
        ]);
    }

    /**
     * Crée une transition (et ses gardes) de façon idempotente.
     */
    protected function transition(WorkflowDefinition $def, string $from, string $to, string $code, array $options = []): WorkflowTransition
    {
        $transition = WorkflowTransition::firstOrCreate(
            ['workflow_definition_id' => $def->getKey(), 'code' => $code],
            [
                'label' => $options['label'] ?? $this->humanize($code),
                'from_state' => $from,
                'to_state' => $to,
                'roles' => $options['roles'] ?? ['admin'],
                'actions' => $options['actions'] ?? [],
                'notifications' => $options['notifications'] ?? [],
                'sort' => $options['sort'] ?? 0,
                'is_active' => true,
            ],
        );

        $this->syncGuards($transition, $options['guards'] ?? []);

        return $transition;
    }

    protected function humanize(string $code): string
    {
        return ucfirst(str_replace('_', ' ', $code));
    }

    protected function syncGuards(WorkflowTransition $transition, array $guards): void
    {
        $current = $transition->guards()
            ->get()
            ->map(fn (WorkflowGuard $g) => $g->rule.':'.json_encode((array) $g->params))
            ->all();

        foreach ($guards as $spec) {
            $key = $spec['rule'].':'.json_encode($spec['params'] ?? []);

            if (in_array($key, $current, true)) {
                continue;
            }

            WorkflowGuard::create([
                'workflow_transition_id' => $transition->getKey(),
                'rule' => $spec['rule'],
                'params' => $spec['params'] ?? [],
                'error_message' => $spec['error_message'] ?? null,
                'is_active' => true,
            ]);

            $current[] = $key;
        }
    }
}
