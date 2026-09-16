<?php

namespace Database\Factories;

use App\Models\EcoleDoctorale;
use App\Models\Universite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EcoleDoctorale>
 */
class EcoleDoctoraleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'universite_id' => Universite::factory(),
            'nom' => fake()->unique()->company().' (École doctorale)',
        ];
    }
}
