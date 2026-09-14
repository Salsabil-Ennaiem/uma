<?php

namespace App\Enums;

enum ReunionStatut: string
{
    case Brouillon = 'brouillon';
    case Planifiee = 'planifiee';
    case EnCours = 'en_cours';
    case Terminee = 'terminee';
    case Annulee = 'annulee';

    public static function transitions(): array
    {
        return [
            self::Brouillon->value => [self::Planifiee, self::Annulee],
            self::Planifiee->value => [self::EnCours, self::Annulee],
            self::EnCours->value => [self::Terminee],
            self::Terminee->value => [],
            self::Annulee->value => [],
        ];
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, self::transitions()[$this->value] ?? [], true);
    }

    public function label(): string
    {
        return match ($this) {
            self::Brouillon => 'Brouillon',
            self::Planifiee => 'Planifiée',
            self::EnCours => 'En cours',
            self::Terminee => 'Terminée',
            self::Annulee => 'Annulée',
        };
    }
}
