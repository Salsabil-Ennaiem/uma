<?php

namespace Database\Factories;

use App\Models\WorkflowDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkflowDefinition>
 */
class WorkflowDefinitionFactory extends Factory
{
    protected $model = WorkflowDefinition::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->slug(2),
            'name' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'subject_type' => \App\Models\Dossier::class,
            'states' => ['initial', 'en_cours', 'termine'],
            'initial_state' => 'initial',
            'is_active' => true,
            'created_by' => null,
        ];
    }
}