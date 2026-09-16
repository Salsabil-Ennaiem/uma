<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['ecole_doctorale_id', 'directeur_id', 'nom'])]
class Etablissement extends Model
{
    protected $table = 'uma_etablissements';

    use HasFactory;

    public function ecoleDoctorale(): BelongsTo
    {
        return $this->belongsTo(EcoleDoctorale::class);
    }

    public function directeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'directeur_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }
}
