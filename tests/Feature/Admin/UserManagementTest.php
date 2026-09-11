<?php

use App\Enums\UserRole;
use App\Models\ActivityLog;
use App\Models\User;

test('guests are redirected from the admin area', function () {
    $this->get(route('admin.users.index'))->assertRedirect(route('login'));
});

test('non-admin users cannot access the admin area', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

test('admins can view the user list', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->create(['name' => 'Budi Hartono']);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee('Budi Hartono')
        ->assertSee($admin->email);
});

test('admins can search users by name or email', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->create(['name' => 'Citra Dewi', 'email' => 'citra@jara.app']);
    User::factory()->create(['name' => 'Dian Permata', 'email' => 'dian@jara.app']);

    $this->actingAs($admin)
        ->get(route('admin.users.index', ['search' => 'citra']))
        ->assertOk()
        ->assertSee('Citra Dewi')
        ->assertDontSee('Dian Permata');

    $this->actingAs($admin)
        ->get(route('admin.users.index', ['search' => 'dian@jara.app']))
        ->assertOk()
        ->assertSee('Dian Permata')
        ->assertDontSee('Citra Dewi');
});

test('admins can filter users by status and role', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->create(['name' => 'Citra Dewi']);
    User::factory()->inactive()->create(['name' => 'Dian Permata']);

    $this->actingAs($admin)
        ->get(route('admin.users.index', ['status' => 'inactive']))
        ->assertOk()
        ->assertSee('Dian Permata')
        ->assertDontSee('Citra Dewi');

    $this->actingAs($admin)
        ->get(route('admin.users.index', ['role' => 'admin']))
        ->assertOk()
        ->assertSee($admin->name)
        ->assertDontSee('Citra Dewi');
});

test('admins can create a user account', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'name' => 'Eko Prasetyo',
            'email' => 'eko@jara.app',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ])
        ->assertRedirect(route('admin.users.index'));

    $user = User::where('email', 'eko@jara.app')->first();

    expect($user)->not->toBeNull()
        ->and($user->role)->toBe(UserRole::User)
        ->and($user->is_active)->toBeTrue();

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $admin->id,
        'target_user_id' => $user->id,
        'action' => 'Created user account',
    ]);
});

test('creating a user validates the input', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->create(['email' => 'taken@jara.app']);

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [])
        ->assertSessionHasErrors(['name', 'email', 'password', 'role']);

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'name' => 'Eko Prasetyo',
            'email' => 'taken@jara.app',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ])
        ->assertSessionHasErrors('email');
});

test('admins can deactivate and reactivate a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->patch(route('admin.users.toggle-status', $user))
        ->assertRedirect();

    expect($user->fresh()->is_active)->toBeFalse();

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $admin->id,
        'target_user_id' => $user->id,
        'action' => 'Deactivated user',
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.users.toggle-status', $user))
        ->assertRedirect();

    expect($user->fresh()->is_active)->toBeTrue();

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $admin->id,
        'target_user_id' => $user->id,
        'action' => 'Reactivated user',
    ]);
});

test('administrator accounts cannot be deactivated', function () {
    $admin = User::factory()->admin()->create();
    $otherAdmin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->patch(route('admin.users.toggle-status', $otherAdmin))
        ->assertForbidden();

    expect($otherAdmin->fresh()->is_active)->toBeTrue();
});

test('admins can view a user detail page', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['name' => 'Farah Nadia']);

    $this->actingAs($admin)
        ->get(route('admin.users.show', $user))
        ->assertOk()
        ->assertSee('Farah Nadia')
        ->assertSee($user->email);
});

test('the activity log tab lists recorded admin actions', function () {
    $admin = User::factory()->admin()->create();

    ActivityLog::create([
        'user_id' => $admin->id,
        'action' => 'Deactivated user',
        'description' => 'Dian Permata (dian@jara.app)',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.users.index', ['tab' => 'activity']))
        ->assertOk()
        ->assertSee('Admin Activity Log')
        ->assertSee('Deactivated user')
        ->assertSee('Dian Permata');
});
