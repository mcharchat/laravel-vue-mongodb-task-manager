<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'progress' => $this->faker->numberBetween(0, 100),
            'completed' => false,
            'priority' => 'none',
            'status' => 'incomplete',
            'category' => 'uncategorized',
            'color' => 'gray-400',
            'icon' => 'fluent:question-24-filled',
            'public' => $this->faker->boolean,
            'project_id' => \App\Models\Project::factory(),
            'assigned_to' => \App\Models\User::factory(),
        ];
    }
}
