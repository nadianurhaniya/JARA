<?php

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use App\Notifications\TaskAssigned;

test('owner dapat menugaskan task ke member proyek', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $taskList->members()->attach($member->id, ['role' => 'member']);
    $task = Task::factory()->for($taskList, 'taskList')->for($owner, 'owner')->create();

    $response = $this->actingAs($owner)->patchJson(
        route('task-lists.tasks.assignee.update', [$taskList, $task]),
        ['assignee_id' => $member->id]
    );

    $response->assertOk();
    expect($task->refresh()->assignee_id)->toBe($member->id);
    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $member->id,
        'type' => TaskAssigned::class,
    ]);
});

test('owner tidak dapat menugaskan task ke bukan member', function () {
    $owner = User::factory()->create();
    $outsider = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $task = Task::factory()->for($taskList, 'taskList')->for($owner, 'owner')->create();

    $response = $this->actingAs($owner)->patchJson(
        route('task-lists.tasks.assignee.update', [$taskList, $task]),
        ['assignee_id' => $outsider->id]
    );

    $response->assertUnprocessable();
});

test('owner tidak dapat menugaskan task ke member nonaktif', function () {
    $owner = User::factory()->create();
    $inactive = User::factory()->inactive()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $taskList->members()->attach($inactive->id, ['role' => 'member']);
    $task = Task::factory()->for($taskList, 'taskList')->for($owner, 'owner')->create();

    $response = $this->actingAs($owner)->patchJson(
        route('task-lists.tasks.assignee.update', [$taskList, $task]),
        ['assignee_id' => $inactive->id]
    );

    $response->assertUnprocessable();
});

test('owner dapat menghapus assignment task', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $taskList->members()->attach($member->id, ['role' => 'member']);
    $task = Task::factory()->for($taskList, 'taskList')->for($owner, 'owner')->create([
        'assignee_id' => $member->id,
    ]);

    $response = $this->actingAs($owner)->patchJson(
        route('task-lists.tasks.assignee.update', [$taskList, $task]),
        ['assignee_id' => null]
    );

    $response->assertOk();
    expect($task->refresh()->assignee_id)->toBeNull();
});

test('member tidak dapat menugaskan task', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $other = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $taskList->members()->attach($member->id, ['role' => 'member']);
    $taskList->members()->attach($other->id, ['role' => 'member']);
    $task = Task::factory()->for($taskList, 'taskList')->for($owner, 'owner')->create();

    $response = $this->actingAs($member)->patchJson(
        route('task-lists.tasks.assignee.update', [$taskList, $task]),
        ['assignee_id' => $other->id]
    );

    $response->assertForbidden();
});
