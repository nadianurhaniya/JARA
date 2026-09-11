<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('login screen can be rendered', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Welcome back');
});

test('users can authenticate with valid credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('users cannot authenticate with an invalid password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('deactivated users cannot authenticate', function () {
    $user = User::factory()->inactive()->create([
        'password' => Hash::make('password123'),
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('login is throttled after too many failed attempts', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $attempt) {
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
    }

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('authenticated users can log out', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

test('guests are redirected to login when visiting the dashboard', function () {
    $this->get('/dashboard')->assertRedirect(route('login'));
});

test('guests are redirected to login from the root path', function () {
    $this->get('/')->assertRedirect(route('login'));
});
