<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class AuditLog extends Model
{
    protected $table = 'uma_audit_logs';

    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'before',
        'after',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'before' => 'array',
            'after' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(
        string $action,
        ?Model $entity = null,
        ?array $before = null,
        ?array $after = null,
        bool $withIp = true,
        ?User $actor = null,
    ): static {
        $actor ??= auth()->user();

        return self::create([
            'user_id' => $actor?->getKey(),
            'action' => $action,
            'entity_type' => $entity?->getMorphClass(),
            'entity_id' => $entity?->getKey(),
            'before' => $before,
            'after' => $after,
            'ip_address' => $withIp ? request()->ip() : null,
        ]);
    }

    public function save(array $options = []): bool
    {
        if ($this->exists) {
            throw new LogicException('Une entrée du journal d\'audit est immuable.');
        }

        return parent::save($options);
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new LogicException('Une entrée du journal d\'audit est immuable.');
    }

    public function delete(): ?bool
    {
        throw new LogicException('Une entrée du journal d\'audit est immuable.');
    }
}
