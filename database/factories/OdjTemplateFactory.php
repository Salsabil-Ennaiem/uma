<?php

namespace Database\Factories;

use App\Models\Commission;
use App\Models\OdjTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OdjTemplate>
 */
class OdjTemplateFactory extends Factory
{
    protected $model = OdjTemplate::class;

    public function definition(): array
    {
        return [
            'label' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'contenu' => "1. Appel et vérification du quorum\n2. Examen des demandes en attente\n3. Adoption des décisions\n4. Questions diverses",
            'commission_id' => Commission::factory(),
            'is_active' => true,
            'created_by' => User::factory(),
        ];
    }
}
