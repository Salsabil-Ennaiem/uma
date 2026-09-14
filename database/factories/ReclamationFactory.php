<?php

namespace Database\Factories;

use App\Models\Reclamation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reclamation>
 */
class ReclamationFactory extends Factory
{
    protected $model = Reclamation::class;

    public function definition(): array
    {
        return [
            'commission_id' => null,
            'user_id' => User::factory(),
            'type' => 'changement_titre',
            'objet' => fake()->sentence(4),
            'contenu' => fake()->paragraph(),
            'urgence' => 'prioritaire',
            'statut' => 'ouverte',
            'closed_at' => null,
            'created_by' => null,
        ];
    }
}