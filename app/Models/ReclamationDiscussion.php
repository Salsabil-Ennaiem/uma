<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Message de discussion autour d'une réclamation (CDC §1.10).
 * Il n'y a pas de modification possible d'un message : création seule.
 */
class ReclamationDiscussion extends Model
{
    protected $table = 'uma_reclamation_discussions';

    public const UPDATED_AT = null;

    protected $fillable = [
        'reclamation_id',
        'user_id',
        'message',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function reclamation(): BelongsTo
    {
        return $this->belongsTo(Reclamation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
