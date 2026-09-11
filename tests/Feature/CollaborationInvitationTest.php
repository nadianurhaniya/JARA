<?php

use App\Models\Invitation;
use App\Models\TaskList;
use App\Models\User;
use App\Notifications\InvitationResponded;
use App\Notifications\MemberInvited;

test('owner dapat mengundang user terdaftar ke proyek', function () {
    $owner = User::factory()->create();
    $invitee = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();

    $response = $this->actingAs($owner)->postJson(route('task-lists.invitations.store', $taskList), [
        'email' => $invitee->email,
    ]);

    $response->assertCreated();
    $this->assertDatabaseHas('invitations', [
        'task_list_id' => $taskList->id,
        'user_id' => $invitee->id,
        'email' => $invitee->email,
        'status' => Invitation::PENDING,
    ]);
    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $invitee->id,
        'type' => MemberInvited::class,
    ]);
});

test('owner tidak dapat mengundang dirinya sendiri', function () {
    $owner = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();

    $response = $this->actingAs($owner)->postJson(route('task-lists.invitations.store', $taskList), [
        'email' => $owner->email,
    ]);

    $response->assertUnprocessable();
});

test('owner tidak dapat mengundang user yang sudah menjadi member', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $taskList->members()->attach($member->id, ['role' => 'member']);

    $response = $this->actingAs($owner)->postJson(route('task-lists.invitations.store', $taskList), [
        'email' => $member->email,
    ]);

    $response->assertUnprocessable();
});

test('owner tidak dapat mengirim undangan pending duplikat', function () {
    $owner = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    Invitation::factory()->for($taskList, 'taskList')->create(['email' => 'farah@jara.app']);

    $response = $this->actingAs($owner)->postJson(route('task-lists.invitations.store', $taskList), [
        'email' => 'farah@jara.app',
    ]);

    $response->assertUnprocessable();
});

test('member tidak dapat mengundang user lain', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $taskList->members()->attach($member->id, ['role' => 'member']);

    $response = $this->actingAs($member)->postJson(route('task-lists.invitations.store', $taskList), [
        'email' => 'farah@jara.app',
    ]);

    $response->assertForbidden();
});

test('user yang diundang dapat menerima undangan', function () {
    $owner = User::factory()->create();
    $invitee = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $invitation = Invitation::factory()->for($taskList, 'taskList')->forUser($invitee)->create();

    $response = $this->actingAs($invitee)->patchJson(route('invitations.update', $invitation), [
        'action' => 'accept',
    ]);

    $response->assertOk();
    $this->assertDatabaseHas('task_list_user', [
        'task_list_id' => $taskList->id,
        'user_id' => $invitee->id,
        'role' => 'member',
    ]);
    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $owner->id,
        'type' => InvitationResponded::class,
    ]);
});

test('user yang diundang dapat menolak undangan', function () {
    $owner = User::factory()->create();
    $invitee = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $invitation = Invitation::factory()->for($taskList, 'taskList')->forUser($invitee)->create();

    $response = $this->actingAs($invitee)->patchJson(route('invitations.update', $invitation), [
        'action' => 'reject',
    ]);

    $response->assertOk();
    expect($invitation->refresh()->status)->toBe(Invitation::REJECTED);
    $this->assertDatabaseMissing('task_list_user', [
        'task_list_id' => $taskList->id,
        'user_id' => $invitee->id,
    ]);
});

test('user lain tidak dapat merespons undangan', function () {
    $owner = User::factory()->create();
    $invitee = User::factory()->create();
    $stranger = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $invitation = Invitation::factory()->for($taskList, 'taskList')->forUser($invitee)->create();

    $response = $this->actingAs($stranger)->patchJson(route('invitations.update', $invitation), [
        'action' => 'accept',
    ]);

    $response->assertForbidden();
});

test('undangan yang sudah diproses tidak dapat diproses ulang', function () {
    $owner = User::factory()->create();
    $invitee = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $invitation = Invitation::factory()->for($taskList, 'taskList')->forUser($invitee)->create([
        'status' => Invitation::ACCEPTED,
    ]);

    $response = $this->actingAs($invitee)->patchJson(route('invitations.update', $invitation), [
        'action' => 'accept',
    ]);

    $response->assertForbidden();
});

test('owner dapat membatalkan undangan pending', function () {
    $owner = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();
    $invitation = Invitation::factory()->for($taskList, 'taskList')->create();

    $response = $this->actingAs($owner)->deleteJson(route('task-lists.invitations.destroy', [$taskList, $invitation]));

    $response->assertOk();
    $this->assertDatabaseMissing('invitations', ['id' => $invitation->id]);
});
