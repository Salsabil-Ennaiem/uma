<?php

namespace Database\Factories;

use App\Models\Commission;
use App\Models\Etablissement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Commission>
 */
class CommissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'etablissement_id' => Etablissement::factory(),
            'president_id' => null,
            'nom' => fake()->unique()->words(3, true),
            'discipline' => fake()->optional()->word(),
            'is_active' => true,
        ];
    }
}
