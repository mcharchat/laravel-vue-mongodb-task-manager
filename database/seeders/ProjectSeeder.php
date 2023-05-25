<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create 5 projects for each user
        \App\Models\User::all()->each(function ($user) {
            \App\Models\Project::factory()->count(5)->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
