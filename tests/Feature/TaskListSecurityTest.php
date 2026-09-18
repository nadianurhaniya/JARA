<?php

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exceptions;

test('input daftar tidak valid ditolak dengan 422 tanpa menulis data', function (array $payload, string $field) {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson(route('task-lists.store'), $payload);

    $response->assertUnprocessable()->assertJsonValidationErrors($field);
    $this->assertDatabaseCount('task_lists', 0);
})->with([
    'nama kosong' => [['description' => 'Valid'], 'name'],
    'nama bukan string' => [['name' => ['invalid']], 'name'],
    'nama terlalu panjang' => [['name' => str_repeat('a', 256)], 'name'],
    'deskripsi bukan string' => [['name' => 'Valid', 'description' => ['invalid']], 'description'],
    'deskripsi terlalu panjang' => [['name' => 'Valid', 'description' => str_repeat('a', 2001)], 'description'],
]);

test('payload SQL injection disimpan sebagai teks tanpa mengubah data lain', function (string $payload) {
    $user = User::factory()->create();
    $existing = TaskList::factory()->for($user, 'owner')->create(['name' => 'Tetap ada']);

    $response = $this->actingAs($user)->postJson(route('task-lists.store'), [
        'name' => $payload,
        'user_id' => User::factory()->create()->id,
    ]);

    $response->assertRedirect(route('task-lists.index'));
    $this->assertDatabaseHas('task_lists', ['user_id' => $user->id, 'name' => $payload]);
    $this->assertModelExists($existing);
})->with(["' OR '1'='1", "'; DROP TABLE task_lists; --"]);

test('parameter route yang bukan id daftar tidak menjalankan penghapusan', function () {
    $user = User::factory()->create();
    $taskList = TaskList::factory()->for($user, 'owner')->create();

    $response = $this->actingAs($user)->deleteJson('/task-lists/1%20OR%201=1');

    $response->assertNotFound();
    $this->assertModelExists($taskList);
});

test('guest tidak dapat membuat atau menghapus daftar', function () {
    $owner = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create();

    $this->post(route('task-lists.store'), ['name' => 'Tidak sah'])->assertRedirect(route('login'));
    $this->delete(route('task-lists.destroy', $taskList))->assertRedirect(route('login'));

    $this->assertDatabaseCount('task_lists', 1);
    $this->assertModelExists($taskList);
});

test('hanya pemilik dapat memperbarui daftar dengan input yang valid', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $taskList = TaskList::factory()->for($owner, 'owner')->create(['name' => 'Sebelum']);

    $this->actingAs($other)->putJson(route('task-lists.update', $taskList), ['name' => 'Asing'])
        ->assertForbidden();
    $this->actingAs($owner)->putJson(route('task-lists.update', $taskList), ['name' => str_repeat('a', 256)])
        ->assertUnprocessable()->assertJsonValidationErrors('name');
    $this->actingAs($owner)->put(route('task-lists.update', $taskList), ['name' => 'Sesudah'])
        ->assertRedirect(route('task-lists.index'));

    $this->assertDatabaseHas('task_lists', ['id' => $taskList->id, 'name' => 'Sesudah']);
});

test('kesalahan database saat membuat daftar dilaporkan dan tidak membocorkan detail', function () {
    $user = User::factory()->create();
    DB::statement("CREATE TRIGGER fail_task_list_insert BEFORE INSERT ON task_lists BEGIN SELECT RAISE(ABORT, 'private database detail'); END");
    Exceptions::fake();

    $response = $this->actingAs($user)->postJson(route('task-lists.store'), ['name' => 'Proyek']);

    $response->assertInternalServerError()
        ->assertJson(['message' => 'Daftar tugas tidak dapat diproses. Silakan coba lagi.'])
        ->assertDontSee('private database detail');
    Exceptions::assertReported(QueryException::class);
    $this->assertDatabaseCount('task_lists', 0);
});
