<?php

namespace Database\Factories;

use App\Models\Commission;
use App\Models\DecisionTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DecisionTemplate>
 */
class DecisionTemplateFactory extends Factory
{
    protected $model = DecisionTemplate::class;

    public function definition(): array
    {
        return [
            'label' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'email_subject' => fake()->sentence(4),
            'email_body' => fake()->paragraph(),
            'commission_id' => Commission::factory(),
            'is_active' => true,
            'created_by' => User::factory(),
        ];
    }
}
