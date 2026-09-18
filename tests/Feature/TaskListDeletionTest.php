<?php

use App\Models\ActivityLog;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;

test('delete daftar beserta seluruh isinya: 3 tasks, 6 subtasks, 4 members terhapus', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();

    $tasks = Task::factory()
        ->for($taskList)
        ->for($user, 'owner')
        ->count(3)
        ->create();

    foreach ($tasks as $task) {
        Subtask::factory()->for($task)->count(2)->create();
    }

    $members = User::factory()->count(4)->create();
    $taskList->members()->attach($members->pluck('id')->all(), ['role' => 'member']);

    expect(Subtask::whereIn('task_id', $tasks->pluck('id'))->count())->toBe(6);

    $response = $this->actingAs($user)->delete(route('task-lists.destroy', $taskList));

    $response->assertRedirect(route('task-lists.index'));
    $this->assertDatabaseMissing('task_lists', ['id' => $taskList->id]);
    $this->assertDatabaseMissing('tasks', ['task_list_id' => $taskList->id]);
    $this->assertDatabaseMissing('task_list_user', ['task_list_id' => $taskList->id]);

    foreach ($tasks as $task) {
        $this->assertDatabaseMissing('subtasks', ['task_id' => $task->id]);
    }

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $user->id,
        'action' => 'task_list.deleted',
    ]);
});

test('delete daftar kosong berhasil', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();

    $response = $this->actingAs($user)->delete(route('task-lists.destroy', $taskList));

    $response->assertRedirect(route('task-lists.index'));
    $this->assertDatabaseMissing('task_lists', ['id' => $taskList->id]);
});

test('delete daftar dengan task tanpa subtask tetap berhasil', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($user, 'owner')->create();

    $response = $this->actingAs($user)->delete(route('task-lists.destroy', $taskList));

    $response->assertRedirect(route('task-lists.index'));
    $this->assertDatabaseMissing('task_lists', ['id' => $taskList->id]);
    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

test('delete daftar A tidak menghapus data daftar B', function () {
    $user = User::factory()->create();

    $listA = TaskList::factory()->for($user, 'owner')->create();
    $taskA = Task::factory()->for($listA)->for($user, 'owner')->create();
    Subtask::factory()->for($taskA)->count(2)->create();

    $listB = TaskList::factory()->for($user, 'owner')->create();
    $taskB = Task::factory()->for($listB)->for($user, 'owner')->create();
    Subtask::factory()->for($taskB)->count(3)->create();
    $listB->members()->attach(User::factory()->create(), ['role' => 'member']);

    $this->actingAs($user)->delete(route('task-lists.destroy', $listA));

    $this->assertDatabaseMissing('task_lists', ['id' => $listA->id]);
    $this->assertDatabaseHas('task_lists', ['id' => $listB->id]);
    $this->assertDatabaseHas('tasks', ['id' => $taskB->id]);
    $this->assertDatabaseHas('subtasks', ['task_id' => $taskB->id]);
    $this->assertDatabaseHas('task_list_user', ['task_list_id' => $listB->id]);
});

test('exception di tengah deletion → seluruh perubahan di-rollback', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($user, 'owner')->create();
    $subtask = Subtask::factory()->for($task)->create();
    $member = User::factory()->create();
    $taskList->members()->attach($member, ['role' => 'member']);

    Event::listen('eloquent.creating: '.ActivityLog::class, function (): void {
        throw new RuntimeException('Kegagalan yang disengaja untuk menguji rollback.');
    });

    $this->actingAs($user)->delete(route('task-lists.destroy', $taskList));

    $this->assertDatabaseHas('task_lists', ['id' => $taskList->id]);
    $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    $this->assertDatabaseHas('subtasks', ['id' => $subtask->id]);
    $this->assertDatabaseHas('task_list_user', ['task_list_id' => $taskList->id]);
    $this->assertDatabaseMissing('activity_logs', ['action' => 'task_list.deleted']);
});

test('FR-40a: tidak ada orphan setelah deletion', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    $tasks = Task::factory()->for($taskList)->for($user, 'owner')->count(2)->create();
    foreach ($tasks as $task) {
        Subtask::factory()->for($task)->count(2)->create();
    }
    $taskList->members()->attach(User::factory()->create(), ['role' => 'member']);

    $this->actingAs($user)->delete(route('task-lists.destroy', $taskList));

    expect(DB::table('tasks')->where('task_list_id', $taskList->id)->count())->toBe(0);

    $orphanedSubtasks = DB::table('subtasks')
        ->join('tasks', 'subtasks.task_id', '=', 'tasks.id')
        ->where('tasks.task_list_id', $taskList->id)
        ->count();
    expect($orphanedSubtasks)->toBe(0);

    expect(DB::table('task_list_user')->where('task_list_id', $taskList->id)->count())->toBe(0);
});