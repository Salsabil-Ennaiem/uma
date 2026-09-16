<?php

namespace App\Models;

use App\Enums\PresenceStatut;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    protected $table = 'uma_presences';

    protected $fillable = [
        'reunion_id',
        'participant_id',
        'email',
        'statut',
        'note',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'statut' => PresenceStatut::class,
        ];
    }

    public function reunion(): BelongsTo
    {
        return $this->belongsTo(Reunion::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopePresent($query)
    {
        return $query->where('statut', PresenceStatut::Present->value);
    }
}
