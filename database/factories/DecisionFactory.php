<?php

namespace Database\Factories;

use App\Models\Decision;
use App\Models\DecisionTemplate;
use App\Models\Dossier;
use App\Models\Reunion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Decision>
 */
class DecisionFactory extends Factory
{
    protected $model = Decision::class;

    public function definition(): array
    {
        return [
            'reunion_id' => Reunion::factory(),
            'dossier_id' => Dossier::factory(),
            'decision_template_id' => DecisionTemplate::factory(),
            'label' => fake()->sentence(3),
            'email_subject' => fake()->sentence(4),
            'email_body' => fake()->paragraph(),
            'annee_inscription' => '3eme',
            'decided_by' => User::factory(),
        ];
    }
}
