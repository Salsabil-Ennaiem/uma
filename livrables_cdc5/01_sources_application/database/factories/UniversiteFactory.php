<?php

namespace Database\Factories;

use App\Models\Universite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Universite>
 */
class UniversiteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => fake()->unique()->company().' Université',
            'code' => fake()->unique()->bothify('UNIV-##'),
        ];
    }
}
