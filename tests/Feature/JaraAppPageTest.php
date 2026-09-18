<?php

use App\Models\User;

test('pengguna yang login dapat mengakses halaman kolaborasi /jara', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('teamcollaboration.app'));

    $response->assertOk();
    $response->assertSee('jara-app');
    $response->assertSee('JARA_SESSION_USER', false);
    $response->assertSee($user->email, false);
});

test('tamu diarahkan ke halaman login saat mengakses /jara', function () {
    $response = $this->get(route('teamcollaboration.app'));

    $response->assertRedirect(route('login'));
});

test('route tab kolaborasi dapat diakses pengguna yang login', function () {
    $user = User::factory()->create();

    $routes = [
        'teamcollaboration.members' => 'members',
        'teamcollaboration.invitations' => 'invitations',
        'teamcollaboration.tasks' => 'tasks',
    ];

    foreach ($routes as $name => $tab) {
        $response = $this->actingAs($user)->get(route($name));

        $response->assertOk();
        $response->assertSee('jara-app');
        $response->assertSee('JARA_INITIAL_TAB', false);
        $response->assertSee('"'.$tab.'"', false);
    }
});

test('tamu diarahkan ke halaman login saat mengakses route tab kolaborasi', function () {
    foreach (['teamcollaboration.members', 'teamcollaboration.invitations', 'teamcollaboration.tasks'] as $name) {
        $this->get(route($name))->assertRedirect(route('login'));
    }
});

test('sidebar dashboard memuat tautan team collaboration', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee(route('teamcollaboration.app'), false);
});
