<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create 5 tasks for each user
        \App\Models\User::all()->each(function ($user) {
            \App\Models\Task::factory()->count(5)->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
