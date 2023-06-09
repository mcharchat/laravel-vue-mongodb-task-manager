<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
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
            'squad_id' => 'd5ade74a-06d1-11ee-be56-0242ac120002',
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];
    }
}
