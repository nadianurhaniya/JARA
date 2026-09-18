<?php

use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

test('user dapat menambahkan sub-tugas', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($user, 'owner')->create();

    $response = $this->actingAs($user)->post(route('subtasks.store', $task), [
        'title' => 'Sub-tugas pertama',
    ]);

    $response->assertRedirect(route('task-lists.tasks.edit', [$taskList, $task]));
    $this->assertDatabaseHas('subtasks', ['task_id' => $task->id, 'title' => 'Sub-tugas pertama']);
});

test('sub-tugas baru mendapat posisi urut sesuai jumlah sub-tugas yang ada', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($user, 'owner')->create();

    $this->actingAs($user)->post(route('subtasks.store', $task), ['title' => 'Sub-tugas 1']);
    $this->actingAs($user)->post(route('subtasks.store', $task), ['title' => 'Sub-tugas 2']);

    $positions = $task->subtasks()->ordered()->pluck('position')->all();

    expect($positions)->toBe([0, 1]);
});

test('user dapat mengedit sub-tugas', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($user, 'owner')->create();
    $subtask = Subtask::factory()->for($task)->create();

    $response = $this->actingAs($user)->patch(route('subtasks.update', $subtask), [
        'title' => 'Judul Baru',
    ]);

    $response->assertRedirect(route('task-lists.tasks.edit', [$taskList, $task]));
    $this->assertDatabaseHas('subtasks', ['id' => $subtask->id, 'title' => 'Judul Baru']);
});

test('user dapat menandai sub-tugas selesai', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($user, 'owner')->create();
    $subtask = Subtask::factory()->for($task)->create(['is_completed' => false]);

    $this->actingAs($user)->patch(route('subtasks.toggle-complete', $subtask));

    expect($subtask->refresh()->is_completed)->toBeTrue();
});

test('user dapat menghapus sub-tugas', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($user, 'owner')->create();
    $subtask = Subtask::factory()->for($task)->create();

    $response = $this->actingAs($user)->delete(route('subtasks.destroy', $subtask));

    $response->assertRedirect(route('task-lists.tasks.edit', [$taskList, $task]));
    $this->assertDatabaseMissing('subtasks', ['id' => $subtask->id]);
});

test('user tidak dapat mengubah sub-tugas milik task user lain', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($owner, 'owner')->create();
    $subtask = Subtask::factory()->for($task)->create();

    $response = $this->actingAs($otherUser)->patch(route('subtasks.toggle-complete', $subtask));

    $response->assertForbidden();
});
