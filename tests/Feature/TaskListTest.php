<?php

use App\Models\TaskList;
use App\Models\User;

test('user dapat membuat daftar/proyek baru', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('task-lists.store'), [
        'name' => 'Proyek Skripsi',
        'description' => 'Menyelesaikan bab 1-3',
    ]);

    $response->assertRedirect(route('task-lists.index'));
    $this->assertDatabaseHas('task_lists', [
        'user_id' => $user->id,
        'name' => 'Proyek Skripsi',
    ]);
});

test('user dapat melihat daftar/proyek miliknya', function () {
    $user = User::factory()->create();
    TaskList::factory()->for($user, 'owner')->create(['name' => 'Proyek A']);

    $response = $this->actingAs($user)->get(route('task-lists.index'));

    $response->assertOk()->assertSee('Proyek A');
});

test('user dapat mengedit daftar/proyek miliknya', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();

    $response = $this->actingAs($user)->put(route('task-lists.update', $taskList), [
        'name' => 'Nama Baru',
        'description' => null,
    ]);

    $response->assertRedirect(route('task-lists.index'));
    $this->assertDatabaseHas('task_lists', ['id' => $taskList->id, 'name' => 'Nama Baru']);
});

test('user dapat menghapus daftar/proyek miliknya', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();

    $response = $this->actingAs($user)->delete(route('task-lists.destroy', $taskList));

    $response->assertRedirect(route('task-lists.index'));
    $this->assertDatabaseMissing('task_lists', ['id' => $taskList->id]);
});

test('user tidak dapat mengedit daftar/proyek milik user lain', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();

    $response = $this->actingAs($otherUser)->put(route('task-lists.update', $taskList), [
        'name' => 'Coba Ubah',
    ]);

    $response->assertForbidden();
});

test('user tidak dapat menghapus daftar/proyek milik user lain', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();

    $response = $this->actingAs($otherUser)->delete(route('task-lists.destroy', $taskList));

    $response->assertForbidden();
    $this->assertDatabaseHas('task_lists', ['id' => $taskList->id]);
});

test('nama daftar/proyek wajib diisi', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('task-lists.store'), [
        'name' => '',
    ]);

    $response->assertSessionHasErrors('name');
});
