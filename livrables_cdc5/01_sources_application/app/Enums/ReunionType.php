<?php

namespace App\Enums;

enum ReunionType: string
{
    case Presentiel = 'presentiel';
    case Visio = 'visio';
    case Hybride = 'hybride';

    public function label(): string
    {
        return match ($this) {
            self::Presentiel => 'Présentiel',
            self::Visio => 'Visio',
            self::Hybride => 'Hybride',
        };
    }
}
