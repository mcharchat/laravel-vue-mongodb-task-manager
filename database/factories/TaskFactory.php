<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project; 
use App\Models\User;

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
        $userIds = User::all()->pluck('id')->toArray();
        $projectIds = Project::all()->pluck('id')->toArray();

        $statusArray = ['Cancelled', 'Not Started', 'In Progress', 'On Hold', 'Completed'];
        $priorityArray = ['None', 'Lowest', 'Low', 'Medium', 'High', 'Highest'] ;

        return [
            'user_id' => $this->faker->randomElement($userIds),
            'title' => $this->faker->sentence,
            'squad_id' => 'd5ade74a-06d1-11ee-be56-0242ac120002',
            'description' => $this->faker->paragraph,
            'progress' => $this->faker->numberBetween(0, 100),
            'completed' => false,
            'priority' => $this->faker->randomElement($priorityArray),
            'status' => $this->faker->randomElement($statusArray),
            'category' => 'Uncategorized',
            'public' => $this->faker->boolean,
            // team is an array of user ids, that may have 0 or more elements
            'team' => $this->faker->randomElements($userIds, $this->faker->numberBetween(0, 10)),
            'project_id' => $this->faker->randomElement($projectIds),
            'assigned_to' => $this->faker->randomElement($userIds),
        ];
    }
}
