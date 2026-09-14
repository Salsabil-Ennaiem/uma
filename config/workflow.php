<?php

use App\Enums\UserRole;

return [

    /*
    |--------------------------------------------------------------------------
    | Moteur de workflow paramétrable (P8)
    |--------------------------------------------------------------------------
    |
    | Les workflows A/B/C sont déclarés en base (workflow_definitions,
    | workflow_transitions, workflow_guards). Ce fichier centralise les
    | constantes et listes de référence (priorités de réclamation, types).
    |
    */

    'reclamations' => [

        'types' => [
            'changement_titre' => 'Changement de titre de thèse',
            'changement_langue' => 'Changement de langue de rédaction',
            'changement_directeur' => 'Changement de directeur de thèse',
            'changement_discipline' => 'Changement de discipline',
            'abandon' => 'Demande d\'abandon',
            'cotutelle' => 'Demande de cotutelle',
        ],

        'priorites' => [
            'tres_urgente' => 'Très urgente',
            'prioritaire' => 'Prioritaire',
            'moyennement_urgente' => 'Moyennement urgente',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rôles pouvant intervenir dans les workflows
    |--------------------------------------------------------------------------
    */

    'roles' => [
        'admin' => UserRole::Admin->value,
        'gestionnaire_ecole' => UserRole::GestionnaireEcole->value,
        'president_commission' => UserRole::PresidentCommission->value,
        'membre_commission' => UserRole::MembreCommission->value,
        'directeur_these' => UserRole::DirecteurThese->value,
        'agent_administration' => UserRole::AgentAdministration->value,
        'doctorant' => UserRole::Doctorant->value,
    ],

];