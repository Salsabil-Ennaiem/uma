<?php

namespace Database\Factories;

use App\Models\EcoleDoctorale;
use App\Models\Etablissement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Etablissement>
 */
class EtablissementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ecole_doctorale_id' => EcoleDoctorale::factory(),
            'directeur_id' => null,
            'nom' => fake()->unique()->company().' (Établissement)',
        ];
    }
}
