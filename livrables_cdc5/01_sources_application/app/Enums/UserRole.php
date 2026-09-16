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

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrateur',
            self::GestionnaireEcole => 'Gestionnaire école doctorale',
            self::PresidentCommission => 'Président de commission',
            self::MembreCommission => 'Membre de commission',
            self::DirecteurThese => 'Directeur de thèse',
            self::Doctorant => 'Doctorant',
            self::AgentAdministration => 'Agent d\'administration',
        };
    }
}
