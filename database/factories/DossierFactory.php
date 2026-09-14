<?php

namespace Database\Factories;

use App\Models\Commission;
use App\Models\Dossier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dossier>
 */
class DossierFactory extends Factory
{
    protected $model = Dossier::class;

    public function definition(): array
    {
        return [
            'doctorant_id' => User::factory(),
            'commission_id' => Commission::factory(),
            'objet' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'statut' => 'en_attente',
            'annee_inscription' => fake()->randomElement(['1ere', '2eme', '3eme', '4eme', '5eme']),
            'created_by' => User::factory(),
        ];
    }
}
