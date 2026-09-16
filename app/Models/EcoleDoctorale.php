<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['universite_id', 'nom'])]
class EcoleDoctorale extends Model
{
    protected $table = 'uma_ecole_doctorales';

    use HasFactory;

    public function universite(): BelongsTo
    {
        return $this->belongsTo(Universite::class);
    }

    public function etablissements(): HasMany
    {
        return $this->hasMany(Etablissement::class);
    }
}
