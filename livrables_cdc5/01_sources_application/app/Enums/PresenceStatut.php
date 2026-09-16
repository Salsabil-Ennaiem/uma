<?php

namespace App\Enums;

enum PresenceStatut: string
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
