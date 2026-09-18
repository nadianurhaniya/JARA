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

test('user mengirim user_id user lain tetap ditetapkan sebagai pemilik sendiri', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $response = $this->actingAs($userA)->post(route('task-lists.store'), [
        'name' => 'Proyek Milik A',
        'description' => 'Deskripsi A',
        'user_id' => $userB->id,
    ]);

    $response->assertRedirect(route('task-lists.index'));

    $taskList = TaskList::where('name', 'Proyek Milik A')->firstOrFail();

    expect($taskList->user_id)->toBe($userA->id);

    $this->assertDatabaseHas('task_lists', [
        'id' => $taskList->id,
        'user_id' => $userA->id,
        'name' => 'Proyek Milik A',
    ]);
});

test('membuat daftar otomatis membuat baris keanggotaan owner di task_list_user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('task-lists.store'), [
        'name' => 'Proyek Dengan Owner Pivot',
        'description' => 'Cek pivot owner',
    ]);

    $response->assertRedirect(route('task-lists.index'));

    $taskList = TaskList::where('name', 'Proyek Dengan Owner Pivot')->firstOrFail();

    $this->assertDatabaseHas('task_list_user', [
        'task_list_id' => $taskList->id,
        'user_id' => $user->id,
        'role' => 'owner',
    ]);
});

test('name kosong ditolak validasi', function () {
    $user = User::factory()->create();

    $countBefore = TaskList::count();

    // Konvensi project ini: FormRequest via web (tanpa Accept: application/json)
    // mengembalikan 302 redirect kembali dengan session errors, bukan 422 JSON.
    $response = $this->actingAs($user)->post(route('task-lists.store'), [
        'description' => 'Tanpa nama',
    ]);

    $response->assertSessionHasErrors('name');
    expect(TaskList::count())->toBe($countBefore);
    $this->assertDatabaseCount('task_lists', $countBefore);
});

test('guest tidak bisa membuat daftar', function () {
    $countBefore = TaskList::count();

    // Route task-lists.store berada dalam grup middleware('auth'),
    // sehingga guest mendapat 302 redirect ke route login.
    $response = $this->post(route('task-lists.store'), [
        'name' => 'Proyek Guest',
        'description' => 'Seharusnya ditolak',
    ]);

    $response->assertRedirect(route('login'));
    $this->assertGuest();
    expect(TaskList::count())->toBe($countBefore);
    $this->assertDatabaseMissing('task_lists', ['name' => 'Proyek Guest']);
});
