<?php

namespace Database\Seeders;

use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        if (app()->environment('local')) {
            TaskList::factory()
                ->count(3)
                ->for($user, 'owner')
                ->has(
                    Task::factory()
                        ->count(5)
                        ->for($user, 'owner')
                        ->has(Subtask::factory()->count(2), 'subtasks')
                )
                ->create();
        }
    }
}
