<?php

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

test('owner yang tidak ditugaskan tidak dapat mengubah status task', function () {
    $owner = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $task = Task::factory()->for($taskList, 'taskList')->for($owner, 'owner')->create([
        'is_completed' => false,
    ]);

    $response = $this->actingAs($owner)->patchJson(
        route('task-lists.tasks.status.update', [$taskList, $task]),
        ['is_completed' => true]
    );

    $response->assertForbidden();
    expect($task->refresh()->is_completed)->toBeFalse();
});

test('member dapat mengubah status task yang ditugaskan kepadanya', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $taskList->members()->attach($member->id, ['role' => 'member']);
    $task = Task::factory()->for($taskList, 'taskList')->for($owner, 'owner')->create([
        'assignee_id' => $member->id,
        'is_completed' => false,
    ]);

    $response = $this->actingAs($member)->patchJson(
        route('task-lists.tasks.status.update', [$taskList, $task]),
        ['is_completed' => true]
    );

    $response->assertOk();
    expect($task->refresh()->is_completed)->toBeTrue();
});

test('member tidak dapat mengubah status task milik member lain', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $other = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $taskList->members()->attach($member->id, ['role' => 'member']);
    $taskList->members()->attach($other->id, ['role' => 'member']);
    $task = Task::factory()->for($taskList, 'taskList')->for($owner, 'owner')->create([
        'assignee_id' => $other->id,
        'is_completed' => false,
    ]);

    $response = $this->actingAs($member)->patchJson(
        route('task-lists.tasks.status.update', [$taskList, $task]),
        ['is_completed' => true]
    );

    $response->assertForbidden();
    expect($task->refresh()->is_completed)->toBeFalse();
});

test('tamu diarahkan ke halaman login saat mengubah status task', function () {
    $owner = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $task = Task::factory()->for($taskList, 'taskList')->for($owner, 'owner')->create();

    $response = $this->patch(route('task-lists.tasks.status.update', [$taskList, $task]), [
        'is_completed' => true,
    ]);

    $response->assertRedirect(route('login'));
});
