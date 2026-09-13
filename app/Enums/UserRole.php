<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case GestionnaireEcole = 'gestionnaire_ecole';
    case PresidentCommission = 'president_commission';
    case MembreCommission = 'membre_commission';
    case DirecteurThese = 'directeur_these';
    case Doctorant = 'doctorant';
    case AgentAdministration = 'agent_administration';
}
