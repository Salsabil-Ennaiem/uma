<?php

namespace App\Models;

use App\Enums\RapportEtatType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * État / rapport paramétrable (CDC §1.13).
 * La mise en page (marges, orientation, format papier, en-tête) est éditée ici ;
 * le rendu passe exclusivement par le moteur du package (PvTemplate + PdfService).
 */
class RapportEtat extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'type',
        'pv_type',
        'description',
        'en_tete',
        'orientation',
        'format_papier',
        'marges',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'type' => RapportEtatType::class,
            'marges' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function margesEffectives(): array
    {
        return array_merge([
            'top' => 20,
            'bottom' => 20,
            'left' => 20,
            'right' => 20,
        ], (array) $this->marges);
    }
}
