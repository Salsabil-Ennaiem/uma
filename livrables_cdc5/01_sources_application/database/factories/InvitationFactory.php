<?php

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\Reunion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invitation>
 */
class InvitationFactory extends Factory
{
    protected $model = Invitation::class;

    public function definition(): array
    {
        return [
            'reunion_id' => Reunion::factory(),
            'participant_id' => User::factory(),
            'email' => null,
            'statut' => 'en_attente',
            'statut_presence' => null,
        ];
    }
}
