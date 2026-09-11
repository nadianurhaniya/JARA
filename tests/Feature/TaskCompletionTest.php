<?php

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

test('user dapat menandai tugas sebagai selesai', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($user, 'owner')->create(['is_completed' => false]);

    $response = $this->actingAs($user)->patch(route('tasks.toggle-complete', [$taskList, $task]));

    $response->assertRedirect(route('task-lists.tasks.index', $taskList));
    $task->refresh();
    expect($task->is_completed)->toBeTrue();
    expect($task->completed_at)->not->toBeNull();
});

test('user dapat menandai tugas sebagai belum selesai', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();
    $task = Task::factory()->completed()->for($taskList)->for($user, 'owner')->create();

    $this->actingAs($user)->patch(route('tasks.toggle-complete', [$taskList, $task]));

    $task->refresh();
    expect($task->is_completed)->toBeFalse();
    expect($task->completed_at)->toBeNull();
});

test('user tidak dapat mengubah status tugas milik user lain', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $task = Task::factory()->for($taskList)->for($owner, 'owner')->create();

    $response = $this->actingAs($otherUser)->patch(route('tasks.toggle-complete', [$taskList, $task]));

    $response->assertForbidden();
});
