<?php

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

test('tugas terurut berdasarkan prioritas secara default', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();

    Task::factory()->for($taskList)->for($user, 'owner')->lowPriority()->create(['title' => 'Tugas Rendah']);
    Task::factory()->for($taskList)->for($user, 'owner')->highPriority()->create(['title' => 'Tugas Tinggi']);
    Task::factory()->for($taskList)->for($user, 'owner')->mediumPriority()->create(['title' => 'Tugas Sedang']);

    $response = $this->actingAs($user)->get(route('task-lists.tasks.index', $taskList));

    $response->assertOk()->assertSeeInOrder(['Tugas Tinggi', 'Tugas Sedang', 'Tugas Rendah']);
});

test('urutan prioritas dapat dibalik dengan direction desc', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();

    Task::factory()->for($taskList)->for($user, 'owner')->lowPriority()->create(['title' => 'Tugas Rendah']);
    Task::factory()->for($taskList)->for($user, 'owner')->highPriority()->create(['title' => 'Tugas Tinggi']);
    Task::factory()->for($taskList)->for($user, 'owner')->mediumPriority()->create(['title' => 'Tugas Sedang']);

    $response = $this->actingAs($user)->get(route('task-lists.tasks.index', $taskList, absolute: false).'?sort=priority&direction=desc');

    $response->assertOk()->assertSeeInOrder(['Tugas Rendah', 'Tugas Sedang', 'Tugas Tinggi']);
});

test('tugas dapat diurutkan berdasarkan tenggat waktu', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();

    Task::factory()->for($taskList)->for($user, 'owner')->create(['title' => 'Jatuh Tempo Jauh', 'due_date' => now()->addMonth()]);
    Task::factory()->for($taskList)->for($user, 'owner')->create(['title' => 'Jatuh Tempo Dekat', 'due_date' => now()->addDay()]);
    Task::factory()->for($taskList)->for($user, 'owner')->create(['title' => 'Tanpa Tenggat', 'due_date' => null]);

    $response = $this->actingAs($user)->get(route('task-lists.tasks.index', $taskList, absolute: false).'?sort=due_date&direction=asc');

    $response->assertOk()->assertSeeInOrder(['Jatuh Tempo Dekat', 'Jatuh Tempo Jauh', 'Tanpa Tenggat']);
});

test('tugas dapat difilter berdasarkan status selesai', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();

    Task::factory()->completed()->for($taskList)->for($user, 'owner')->create(['title' => 'Tugas Kelar']);
    Task::factory()->for($taskList)->for($user, 'owner')->create(['title' => 'Tugas Pending', 'is_completed' => false]);

    $response = $this->actingAs($user)->get(route('task-lists.tasks.index', $taskList, absolute: false).'?status=completed');

    $response->assertOk()->assertSee('Tugas Kelar')->assertDontSee('Tugas Pending');
});

test('tugas dapat difilter berdasarkan status belum selesai', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();

    Task::factory()->completed()->for($taskList)->for($user, 'owner')->create(['title' => 'Tugas Kelar']);
    Task::factory()->for($taskList)->for($user, 'owner')->create(['title' => 'Tugas Pending', 'is_completed' => false]);

    $response = $this->actingAs($user)->get(route('task-lists.tasks.index', $taskList, absolute: false).'?status=incomplete');

    $response->assertOk()->assertSee('Tugas Pending')->assertDontSee('Tugas Kelar');
});
