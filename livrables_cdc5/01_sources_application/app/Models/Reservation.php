<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Réservation d'une salle ou d'un membre de jury pour une soutenance.
 * Sert au contrôle de chevauchement jury/salles (Workflow B, exigence CDC §10).
 */
class Reservation extends Model
{
    use HasFactory;

    public const TYPE_SALLE = 'salle';
    public const TYPE_JURY = 'jury';

    protected $fillable = [
        'type',
        'salle',
        'membre_id',
        'date_debut',
        'date_fin',
        'objet',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'datetime',
            'date_fin' => 'datetime',
        ];
    }

    public function membre(): BelongsTo
    {
        return $this->belongsTo(User::class, 'membre_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Chevauche les réservations de même type/cible sur la plage donnée.
     */
    public function scopeChevauche(Builder $query, string $type, ?string $salle = null, ?int $membreId = null, $debut = null, $fin = null): Builder
    {
        return $query
            ->where('type', $type)
            ->when($salle !== null && $type === self::TYPE_SALLE, fn (Builder $q) => $q->where('salle', $salle))
            ->when($membreId !== null && $type === self::TYPE_JURY, fn (Builder $q) => $q->where('membre_id', $membreId))
            ->when($debut !== null || $fin !== null, function (Builder $q) use ($debut, $fin) {
                $q->where(function (Builder $inner) use ($debut, $fin) {
                    if ($debut !== null) {
                        $inner->where('date_fin', '>', $debut);
                    }
                    if ($fin !== null) {
                        $inner->where('date_debut', '<', $fin);
                    }
                });
            });
    }
}