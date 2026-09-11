<?php

namespace Database\Seeders;

use App\Enums\UserRole;
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
        User::factory()->admin()->create([
            'name' => 'Arya Santoso',
            'email' => 'admin@jara.app',
        ]);

        $budi = User::factory()->create([
            'name' => 'Budi Hartono',
            'email' => 'budi@jara.app',
        ]);

        User::factory()->inactive()->create([
            'name' => 'Dian Permata',
            'email' => 'dian@jara.app',
        ]);

        User::factory()->create([
            'name' => 'Citra Dewi',
            'email' => 'citra@jara.app',
            'role' => UserRole::User,
        ]);

        if (app()->environment('local')) {
            TaskList::factory()
                ->count(3)
                ->for($budi, 'owner')
                ->has(
                    Task::factory()
                        ->count(5)
                        ->for($budi, 'owner')
                        ->has(Subtask::factory()->count(2), 'subtasks')
                )
                ->create();
        }
    }
}
