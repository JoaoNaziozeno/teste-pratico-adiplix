<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $people = Person::factory(10)->create();
        $tasks = Task::factory(30)->create();

        foreach ($people as $person) {
            $person->tasks()->attach(
                $tasks->random(rand(1, 5))->pluck('id')->toArray()
            );
        } 
    }
}
