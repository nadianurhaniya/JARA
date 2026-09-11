<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

test('forgot password screen can be rendered', function () {
    $this->get('/forgot-password')
        ->assertOk()
        ->assertSee('Reset password');
});

test('a reset link is sent for a registered email', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email])
        ->assertSessionHasNoErrors();

    Notification::assertSentTo($user, ResetPassword::class);
});

test('no reset link is sent for an unknown email', function () {
    Notification::fake();

    $this->post('/forgot-password', ['email' => 'nobody@jara.app'])
        ->assertSessionHasErrors('email');

    Notification::assertNothingSent();
});

test('reset password screen can be rendered with a token', function () {
    $this->get('/reset-password/'.Str::random(60).'?email=someone@jara.app')
        ->assertOk()
        ->assertSee('Set new password');
});

test('the password can be reset with a valid token', function () {
    Notification::fake();

    $user = User::factory()->create();
    $token = null;

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$token) {
        $token = $notification->token;

        return true;
    });

    $this->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ])->assertRedirect(route('login'));

    expect(Hash::check('new-password123', $user->fresh()->password))->toBeTrue();
});

test('the password cannot be reset with an invalid token', function () {
    $user = User::factory()->create();

    $this->post('/reset-password', [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ])->assertSessionHasErrors('email');

    expect(Hash::check('new-password123', $user->fresh()->password))->toBeFalse();
});
