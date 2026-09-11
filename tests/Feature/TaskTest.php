<?php

use App\Enums\TaskPriority;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

test('user dapat menambahkan tugas baru ke daftar/proyek miliknya', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();

    $response = $this->actingAs($user)->post(route('task-lists.tasks.store', $taskList), [
        'title' => 'Tulis bab 1',
        'description' => 'Pendahuluan',
        'priority' => TaskPriority::High->value,
        'due_date' => now()->addWeek()->format('Y-m-d'),
    ]);

    $response->assertRedirect(route('task-lists.tasks.index', $taskList));
    $this->assertDatabaseHas('tasks', [
        'task_list_id' => $taskList->id,
        'user_id' => $user->id,
        'title' => 'Tulis bab 1',
        'priority' => 'high',
    ]);
});

test('user tidak dapat menambahkan tugas ke daftar/proyek milik user lain', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();

    $response = $this->actingAs($otherUser)->post(route('task-lists.tasks.store', $taskList), [
        'title' => 'Coba tambah',
        'priority' => TaskPriority::Low->value,
    ]);

    $response->assertForbidden();
});

test('user dapat mengedit detail tugas', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($user, 'owner')->create();

    $response = $this->actingAs($user)->put(route('task-lists.tasks.update', [$taskList, $task]), [
        'title' => 'Judul Baru',
        'description' => 'Deskripsi baru',
        'priority' => TaskPriority::Medium->value,
        'due_date' => null,
    ]);

    $response->assertRedirect(route('task-lists.tasks.index', $taskList));
    $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Judul Baru', 'priority' => 'medium']);
});

test('user tidak dapat mengedit tugas milik user lain', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($owner, 'owner')->create();

    $response = $this->actingAs($otherUser)->put(route('task-lists.tasks.update', [$taskList, $task]), [
        'title' => 'Coba Ubah',
        'priority' => TaskPriority::Low->value,
    ]);

    $response->assertForbidden();
});

test('user dapat menghapus tugas', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($user, 'owner')->create();

    $response = $this->actingAs($user)->delete(route('task-lists.tasks.destroy', [$taskList, $task]));

    $response->assertRedirect(route('task-lists.tasks.index', $taskList));
    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

test('user tidak dapat menghapus tugas milik user lain', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($owner, 'owner')->create();

    $response = $this->actingAs($otherUser)->delete(route('task-lists.tasks.destroy', [$taskList, $task]));

    $response->assertForbidden();
    $this->assertDatabaseHas('tasks', ['id' => $task->id]);
});

test('judul tugas wajib diisi', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();

    $response = $this->actingAs($user)->post(route('task-lists.tasks.store', $taskList), [
        'title' => '',
        'priority' => TaskPriority::Low->value,
    ]);

    $response->assertSessionHasErrors('title');
});
