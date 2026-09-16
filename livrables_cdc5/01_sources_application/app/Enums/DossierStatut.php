<?php

namespace App\Enums;

enum DossierStatut: string
{
    case EnAttente = 'en_attente';
    case EnCours = 'en_cours';
    case Traite = 'traite';

    public function label(): string
    {
        return match ($this) {
            self::EnAttente => 'En attente',
            self::EnCours => 'En cours',
            self::Traite => 'Traité',
        };
    }
}
