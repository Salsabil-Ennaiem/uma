<?php

namespace App\Models;

use App\Enums\InvitationPresence;
use App\Enums\InvitationStatut;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $table = 'uma_invitations';

    protected $fillable = [
        'reunion_id',
        'participant_id',
        'email',
        'statut',
        'statut_presence',
        'commentaire',
        'note',
        'excuse_status',
        'excuse_validated_by',
        'excuse_validated_at',
        'piece_joint',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'statut' => InvitationStatut::class,
            'statut_presence' => InvitationPresence::class,
            'excuse_validated_at' => 'datetime',
            'sent_at' => 'datetime',
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

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'excuse_validated_by');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', InvitationStatut::EnAttente->value);
    }
}
