<?php

namespace App\Enums;

enum InvitationStatut: string
{
    case EnAttente = 'en_attente';
    case Acceptee = 'acceptee';
    case Refusee = 'refusee';
    case Excuse = 'excuse';

    public function label(): string
    {
        return match ($this) {
            self::EnAttente => 'En attente',
            self::Acceptee => 'Acceptée',
            self::Refusee => 'Refusée',
            self::Excuse => 'Excusée',
        };
    }
}

enum InvitationPresence: string
{
    case Present = 'present';
    case Absent = 'absent';
    case Excuse = 'excuse';

    public function label(): string
    {
        return match ($this) {
            self::Present => 'Présent',
            self::Absent => 'Absent',
            self::Excuse => 'Excusé',
        };
    }
}
