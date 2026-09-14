<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'type' => Reservation::TYPE_SALLE,
            'salle' => fake()->randomElement(['Salle A', 'Salle B', 'Amphi 1']),
            'membre_id' => null,
            'date_debut' => now()->addDays(7)->setTime(9, 0),
            'date_fin' => now()->addDays(7)->setTime(11, 0),
            'objet' => fake()->sentence(3),
            'created_by' => null,
        ];
    }
}