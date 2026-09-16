<?php

namespace Database\Factories;

use App\Models\Presence;
use App\Models\Reunion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Presence>
 */
class PresenceFactory extends Factory
{
    protected $model = Presence::class;

    public function definition(): array
    {
        return [
            'reunion_id' => Reunion::factory(),
            'participant_id' => User::factory(),
            'email' => null,
            'statut' => 'present',
            'recorded_by' => User::factory(),
        ];
    }
}
