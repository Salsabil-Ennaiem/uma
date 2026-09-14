<?php

namespace Database\Factories;

use App\Models\Commission;
use App\Models\Reunion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reunion>
 */
class ReunionFactory extends Factory
{
    protected $model = Reunion::class;

    public function definition(): array
    {
        return [
            'commission_id' => Commission::factory(),
            'objet' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'ordre_du_jour' => "1. Appel et vérification du quorum\n2. Examen des demandes\n3. Décisions\n4. Divers",
            'date_debut' => $debut = fake()->dateTimeBetween('+2 weeks', '+4 weeks'),
            'date_fin' => (clone $debut)->modify('+2 hours'),
            'lieu' => fake()->city().', salle '.fake()->randomDigitNotNull(),
            'type' => 'presentiel',
            'statut' => 'brouillon',
            'created_by' => User::factory(),
        ];
    }

    public function planifiee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'planifiee',
        ]);
    }

    public function terminee(): static
    {
        return $this->state(function (array $attributes) {
            $fin = fake()->dateTimeBetween('-1 week', '-1 day');

            return [
                'statut' => 'terminee',
                'date_debut' => (clone $fin)->modify('-2 hours'),
                'date_fin' => $fin,
            ];
        });
    }
}
