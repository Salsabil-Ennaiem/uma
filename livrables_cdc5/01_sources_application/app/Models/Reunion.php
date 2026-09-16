<?php

namespace App\Models;

use App\Enums\ReunionStatut;
use App\Enums\ReunionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SalsabilEnnaiem\PvModule\Models\Pv;

class Reunion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'commission_id',
        'objet',
        'description',
        'odj_template_id',
        'ordre_du_jour',
        'date_debut',
        'date_fin',
        'lieu',
        'lien',
        'type',
        'statut',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'datetime',
            'date_fin' => 'datetime',
            'type' => ReunionType::class,
            'statut' => ReunionStatut::class,
        ];
    }

    public function commission(): BelongsTo
    {
        return $this->belongsTo(Commission::class);
    }

    public function odjTemplate(): BelongsTo
    {
        return $this->belongsTo(OdjTemplate::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function dossiers(): BelongsToMany
    {
        return $this->belongsToMany(Dossier::class, 'reunion_dossier')
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    public function decisions(): HasMany
    {
        return $this->hasMany(Decision::class);
    }

    public function pvs()
    {
        return (new Pv)->newQuery()
            ->where('source_type', 'reunion')
            ->where('source_id', $this->getKey());
    }

    public function participantsEnsemble(): array
    {
        return [
            'commission_id' => $this->commission_id,
        ];
    }

    public function estPassee(): bool
    {
        return $this->date_fin !== null && $this->date_fin->lt(now());
    }
}
