<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Decision extends Model
{
    use HasFactory;

    protected $fillable = [
        'reunion_id',
        'dossier_id',
        'decision_template_id',
        'label',
        'email_subject',
        'email_body',
        'annee_inscription',
        'decided_by',
    ];

    public function reunion(): BelongsTo
    {
        return $this->belongsTo(Reunion::class);
    }

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(DecisionTemplate::class, 'decision_template_id');
    }

    public function decideur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }
}
