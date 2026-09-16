<?php

namespace App\Enums;

enum RapportEtatType: string
{
    case Rapport = 'rapport';
    case Etat = 'etat';

    public function label(): string
    {
        return match ($this) {
            self::Rapport => 'Rapport',
            self::Etat => 'État',
        };
    }
}
