<?php

use App\Models\User;

test('pengguna yang login dapat mengakses halaman kolaborasi /jara', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('jara.app'));

    $response->assertOk();
    $response->assertSee('jara-app');
});

test('tamu diarahkan ke halaman login saat mengakses /jara', function () {
    $response = $this->get(route('jara.app'));

    $response->assertRedirect(route('login'));
});
