<?php

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

test('tugas yang lewat tenggat waktu berstatus overdue', function () {
    $task = Task::factory()->overdue()->make();

    expect($task->isOverdue())->toBeTrue();
    expect($task->isDueSoon())->toBeFalse();
    expect($task->dueStatus())->toBe('overdue');
});

test('tugas yang mendekati tenggat waktu berstatus due soon', function () {
    $task = Task::factory()->dueSoon()->make();

    expect($task->isOverdue())->toBeFalse();
    expect($task->isDueSoon())->toBeTrue();
    expect($task->dueStatus())->toBe('due_soon');
});

test('tugas yang sudah selesai tidak dianggap overdue meski tenggat lewat', function () {
    $task = Task::factory()->overdue()->completed()->make();

    expect($task->isOverdue())->toBeFalse();
    expect($task->dueStatus())->toBeNull();
});

test('tugas tanpa tenggat waktu tidak memiliki indikator', function () {
    $task = Task::factory()->make(['due_date' => null, 'is_completed' => false]);

    expect($task->dueStatus())->toBeNull();
});

test('indikator terlambat tampil di halaman daftar tugas', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    Task::factory()->overdue()->for($taskList)->for($user, 'owner')->create(['title' => 'Tugas Terlambat']);

    $response = $this->actingAs($user)->get(route('task-lists.tasks.index', $taskList));

    $response->assertOk()->assertSee('Terlambat');
});

test('indikator jatuh tempo segera tampil di halaman daftar tugas', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    Task::factory()->dueSoon()->for($taskList)->for($user, 'owner')->create(['title' => 'Tugas Mendesak']);

    $response = $this->actingAs($user)->get(route('task-lists.tasks.index', $taskList));

    $response->assertOk()->assertSee('Jatuh Tempo Segera');
});
