<?php

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use App\Notifications\RemovedFromProject;

test('owner dapat melihat daftar member proyek', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $taskList->members()->attach($member->id, ['role' => 'member']);

    $response = $this->actingAs($owner)->getJson(route('task-lists.members.index', $taskList));

    $response->assertOk()
        ->assertJsonFragment(['email' => $owner->email, 'role' => 'owner'])
        ->assertJsonFragment(['email' => $member->email, 'role' => 'member']);
});

test('member dapat melihat daftar member proyek', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $taskList->members()->attach($member->id, ['role' => 'member']);

    $response = $this->actingAs($member)->getJson(route('task-lists.members.index', $taskList));

    $response->assertOk();
});

test('bukan member tidak dapat melihat daftar member proyek', function () {
    $owner = User::factory()->create();
    $outsider = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();

    $response = $this->actingAs($outsider)->getJson(route('task-lists.members.index', $taskList));

    $response->assertForbidden();
});

test('owner dapat menghapus member dari proyek', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $taskList->members()->attach($member->id, ['role' => 'member']);

    $response = $this->actingAs($owner)->deleteJson(route('task-lists.members.destroy', [$taskList, $member]));

    $response->assertOk();
    $this->assertDatabaseMissing('task_list_user', [
        'task_list_id' => $taskList->id,
        'user_id' => $member->id,
    ]);
    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $member->id,
        'type' => RemovedFromProject::class,
    ]);
});

test('tugas milik member yang dihapus menjadi unassigned', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $taskList->members()->attach($member->id, ['role' => 'member']);
    $task = Task::factory()->for($taskList, 'taskList')->for($owner, 'owner')->create([
        'assignee_id' => $member->id,
    ]);

    $this->actingAs($owner)->deleteJson(route('task-lists.members.destroy', [$taskList, $member]));

    expect($task->refresh()->assignee_id)->toBeNull();
});

test('owner tidak dapat menghapus dirinya sendiri', function () {
    $owner = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();

    $response = $this->actingAs($owner)->deleteJson(route('task-lists.members.destroy', [$taskList, $owner]));

    $response->assertUnprocessable();
    $this->assertDatabaseHas('task_list_user', [
        'task_list_id' => $taskList->id,
        'user_id' => $owner->id,
    ]);
});

test('member tidak dapat menghapus member lain', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $other = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $taskList->members()->attach($member->id, ['role' => 'member']);
    $taskList->members()->attach($other->id, ['role' => 'member']);

    $response = $this->actingAs($member)->deleteJson(route('task-lists.members.destroy', [$taskList, $other]));

    $response->assertForbidden();
});
