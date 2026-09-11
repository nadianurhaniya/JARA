<?php

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use App\Notifications\TaskAssigned;

test('user dapat melihat notifikasinya sendiri', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $other = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $task = Task::factory()->for($taskList, 'taskList')->for($owner, 'owner')->create();

    $member->notify(new TaskAssigned($task, $owner));
    $member->notify(new TaskAssigned($task, $owner));
    $other->notify(new TaskAssigned($task, $owner));

    $response = $this->actingAs($member)->getJson(route('notifications.index'));

    $response->assertOk()->assertJsonCount(2, 'data');
});

test('user dapat menandai notifikasinya sudah dibaca', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $task = Task::factory()->for($taskList, 'taskList')->for($owner, 'owner')->create();
    $member->notify(new TaskAssigned($task, $owner));
    $notification = $member->notifications()->first();

    $response = $this->actingAs($member)->patchJson(route('notifications.update', $notification));

    $response->assertOk();
    expect($notification->refresh()->read_at)->not->toBeNull();
});

test('user tidak dapat menandai notifikasi milik orang lain', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $other = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $task = Task::factory()->for($taskList, 'taskList')->for($owner, 'owner')->create();
    $member->notify(new TaskAssigned($task, $owner));
    $notification = $member->notifications()->first();

    $response = $this->actingAs($other)->patchJson(route('notifications.update', $notification));

    $response->assertNotFound();
});
