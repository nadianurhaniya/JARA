<?php

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

function buatDaftarBerisi(User $owner): TaskList
{
    $taskList = TaskList::factory()->for($owner, 'owner')->create(['name' => 'Proyek Skripsi']);
    Task::factory()->count(2)->for($taskList)->for($owner, 'owner')->create();

    return $taskList;
}

/*
|--------------------------------------------------------------------------
| FR-42: Guest ditolak (redirect login / 401), tanpa efek samping
|--------------------------------------------------------------------------
*/

test('guest tidak dapat membuka halaman daftar/proyek', function () {
    $this->get(route('task-lists.index'))->assertRedirect(route('login'));
});

test('guest tidak dapat membuka form buat daftar/proyek', function () {
    $this->get(route('task-lists.create'))->assertRedirect(route('login'));
});

test('guest tidak dapat membuat daftar/proyek dan tidak ada data yang dibuat', function () {
    $response = $this->post(route('task-lists.store'), [
        'name' => 'Proyek Tamu',
        'description' => 'Tidak boleh tersimpan',
    ]);

    $response->assertRedirect(route('login'));
    $this->assertDatabaseCount('task_lists', 0);
    $this->assertDatabaseCount('task_list_user', 0);
});

test('guest yang mengirim request json menerima 401', function () {
    $this->postJson(route('task-lists.store'), ['name' => 'Proyek Tamu'])
        ->assertUnauthorized();

    $this->assertDatabaseCount('task_lists', 0);
});

test('guest tidak dapat membuka form edit daftar/proyek', function () {
    $taskList = TaskList::factory()->create();

    $this->get(route('task-lists.edit', $taskList))->assertRedirect(route('login'));
});

test('guest tidak dapat mengubah daftar/proyek', function () {
    $taskList = TaskList::factory()->create(['name' => 'Nama Asli']);

    $this->put(route('task-lists.update', $taskList), ['name' => 'Diubah Tamu'])
        ->assertRedirect(route('login'));

    $this->assertDatabaseHas('task_lists', ['id' => $taskList->id, 'name' => 'Nama Asli']);
});

test('guest tidak dapat menghapus daftar/proyek beserta isinya', function () {
    $owner = User::factory()->create();
    $taskList = buatDaftarBerisi($owner);

    $this->delete(route('task-lists.destroy', $taskList))->assertRedirect(route('login'));

    $this->assertDatabaseHas('task_lists', ['id' => $taskList->id]);
    $this->assertDatabaseCount('tasks', 2);
    $this->assertDatabaseHas('task_list_user', ['task_list_id' => $taskList->id, 'user_id' => $owner->id]);
});

/*
|--------------------------------------------------------------------------
| FR-42: User terautentikasi yang bukan pemilik ditolak 403, data utuh
|--------------------------------------------------------------------------
*/

test('user lain tidak dapat membuka form edit daftar/proyek milik orang lain', function () {
    $taskList = TaskList::factory()->create();
    $other = User::factory()->create();

    $this->actingAs($other)->get(route('task-lists.edit', $taskList))->assertForbidden();
});

test('user lain tidak dapat mengubah daftar/proyek milik orang lain', function () {
    $taskList = TaskList::factory()->create(['name' => 'Nama Asli']);
    $other = User::factory()->create();

    $this->actingAs($other)
        ->put(route('task-lists.update', $taskList), ['name' => 'Diubah Orang Lain'])
        ->assertForbidden();

    $this->assertDatabaseHas('task_lists', ['id' => $taskList->id, 'name' => 'Nama Asli']);
});

test('user lain tidak dapat menghapus daftar/proyek milik orang lain dan seluruh isinya tetap ada', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $taskList = buatDaftarBerisi($owner);

    $this->actingAs($other)->delete(route('task-lists.destroy', $taskList))->assertForbidden();

    $this->assertDatabaseHas('task_lists', ['id' => $taskList->id, 'user_id' => $owner->id]);
    $this->assertDatabaseCount('tasks', 2);
    $this->assertDatabaseHas('task_list_user', [
        'task_list_id' => $taskList->id,
        'user_id' => $owner->id,
        'role' => 'owner',
    ]);
});

test('member kolaborasi yang bukan pemilik tidak dapat menghapus daftar/proyek', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $taskList = buatDaftarBerisi($owner);
    $taskList->members()->attach($member, ['role' => 'member']);

    $this->actingAs($member)->delete(route('task-lists.destroy', $taskList))->assertForbidden();

    $this->assertDatabaseHas('task_lists', ['id' => $taskList->id]);
    $this->assertDatabaseCount('tasks', 2);
    $this->assertDatabaseCount('task_list_user', 2);
});

test('admin tidak memiliki wewenang menghapus daftar/proyek milik user', function () {
    $owner = User::factory()->create();
    $admin = User::factory()->admin()->create();
    $taskList = buatDaftarBerisi($owner);

    $this->actingAs($admin)->delete(route('task-lists.destroy', $taskList))->assertForbidden();

    $this->assertDatabaseHas('task_lists', ['id' => $taskList->id]);
});

test('pemilik tetap dapat menghapus daftar/proyeknya sendiri', function () {
    $owner = User::factory()->create();
    $taskList = buatDaftarBerisi($owner);

    $this->actingAs($owner)
        ->delete(route('task-lists.destroy', $taskList))
        ->assertRedirect(route('task-lists.index'));

    $this->assertDatabaseMissing('task_lists', ['id' => $taskList->id]);
});

/*
|--------------------------------------------------------------------------
| FR-42a: Policy bersifat fail-closed, dicek sebelum operasi tulis
|--------------------------------------------------------------------------
*/

test('policy menolak guest untuk membuat dan menghapus daftar/proyek', function () {
    $taskList = TaskList::factory()->create();

    expect(Gate::forUser(null)->allows('create', TaskList::class))->toBeFalse()
        ->and(Gate::forUser(null)->allows('viewAny', TaskList::class))->toBeFalse()
        ->and(Gate::forUser(null)->allows('delete', $taskList))->toBeFalse();
});

test('policy hanya mengizinkan pemilik untuk view, update, dan delete', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();

    expect(Gate::forUser($other)->allows('view', $taskList))->toBeFalse()
        ->and(Gate::forUser($other)->allows('update', $taskList))->toBeFalse()
        ->and(Gate::forUser($other)->allows('delete', $taskList))->toBeFalse()
        ->and(Gate::forUser($owner)->allows('view', $taskList))->toBeTrue()
        ->and(Gate::forUser($owner)->allows('update', $taskList))->toBeTrue()
        ->and(Gate::forUser($owner)->allows('delete', $taskList))->toBeTrue();
});

test('policy mengizinkan user login untuk viewAny dan create', function () {
    $user = User::factory()->create();

    expect(Gate::forUser($user)->allows('viewAny', TaskList::class))->toBeTrue()
        ->and(Gate::forUser($user)->allows('create', TaskList::class))->toBeTrue();
});
