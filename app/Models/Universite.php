<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nom', 'code'])]
class Universite extends Model
{
    protected $table = 'uma_universites';

    use HasFactory;

    public function ecoleDoctorales(): HasMany
    {
        return $this->hasMany(EcoleDoctorale::class);
    }
}
