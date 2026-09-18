<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use App\Notifications\TaskAssigned;
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

        $citra = User::factory()->create([
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

            $this->seedCollaboration($budi, $citra);
        }
    }

    /**
     * Data demo modul Kolaborasi & Kepemilikan (FR-19 sampai FR-26).
     *
     * Catatan: seeder berjalan dengan WithoutModelEvents sehingga
     * baris pivot owner/member ditulis eksplisit di sini.
     */
    protected function seedCollaboration(User $owner, User $member): void
    {
        $project = TaskList::factory()->for($owner, 'owner')->create([
            'name' => 'Project A',
            'description' => 'Proyek demo kolaborasi tim JARA.',
        ]);

        $project->members()->syncWithoutDetaching([
            $owner->id => ['role' => 'owner'],
            $member->id => ['role' => 'member'],
        ]);

        $project->invitations()->create([
            'user_id' => null,
            'email' => 'farah@jara.app',
            'status' => 'pending',
        ]);

        $task = Task::factory()
            ->for($project, 'taskList')
            ->for($owner, 'owner')
            ->create(['title' => 'Design Login Page']);

        $task->assignee()->associate($member);
        $task->save();

        $member->notify(new TaskAssigned($task->refresh(), $owner));
    }
}
