<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('registration screen can be rendered', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSee('Create account');
});

test('new users can register with a name, email and password', function () {
    $response = $this->post('/register', [
        'name' => 'Budi Hartono',
        'email' => 'budi@jara.app',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();

    $user = User::where('email', 'budi@jara.app')->first();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('Budi Hartono')
        ->and($user->role)->toBe(UserRole::User)
        ->and($user->is_active)->toBeTrue()
        ->and(Hash::check('password123', $user->password))->toBeTrue();
});

test('registration requires a name, email and password', function () {
    $this->post('/register', [])
        ->assertSessionHasErrors(['name', 'email', 'password']);
});

test('registration rejects an invalid email address', function () {
    $this->post('/register', [
        'name' => 'Budi Hartono',
        'email' => 'not-an-email',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('email');
});

test('registration rejects an email that is already taken', function () {
    User::factory()->create(['email' => 'taken@jara.app']);

    $this->post('/register', [
        'name' => 'Budi Hartono',
        'email' => 'taken@jara.app',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('email');
});

test('registration requires a password of at least eight characters', function () {
    $this->post('/register', [
        'name' => 'Budi Hartono',
        'email' => 'budi@jara.app',
        'password' => 'short',
        'password_confirmation' => 'short',
    ])->assertSessionHasErrors('password');
});

test('registration requires the password to be confirmed', function () {
    $this->post('/register', [
        'name' => 'Budi Hartono',
        'email' => 'budi@jara.app',
        'password' => 'password123',
        'password_confirmation' => 'different-password',
    ])->assertSessionHasErrors('password');
});

test('authenticated users are redirected away from the registration screen', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/register')
        ->assertRedirect(route('dashboard'));
});
