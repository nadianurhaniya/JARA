<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskAssignmentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskListMemberController;
use App\Http\Controllers\TaskStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::prefix('teamcollaboration')->name('teamcollaboration.')->group(function () {
        Route::get('', function () {
            return view('jara.app');
        })->name('app');

        Route::get('members', function () {
            return view('jara.app', ['tab' => 'members']);
        })->name('members');

        Route::get('invitations', function () {
            return view('jara.app', ['tab' => 'invitations']);
        })->name('invitations');

        Route::get('tasks', function () {
            return view('jara.app', ['tab' => 'tasks']);
        })->name('tasks');
    });

    Route::resource('task-lists', TaskListController::class)->except('show');

    Route::resource('task-lists.tasks', TaskController::class)
        ->except('show')
        ->scoped(['task' => 'id']);

    Route::patch('task-lists/{taskList}/tasks/{task}/toggle-complete', [TaskController::class, 'toggleComplete'])
        ->name('tasks.toggle-complete');

    Route::post('tasks/{task}/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
    Route::patch('subtasks/{subtask}', [SubtaskController::class, 'update'])->name('subtasks.update');
    Route::patch('subtasks/{subtask}/toggle-complete', [SubtaskController::class, 'toggleComplete'])->name('subtasks.toggle-complete');
    Route::delete('subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');

    Route::get('task-lists/{taskList}/members', [TaskListMemberController::class, 'index'])->name('task-lists.members.index');
    Route::delete('task-lists/{taskList}/members/{user}', [TaskListMemberController::class, 'destroy'])->name('task-lists.members.destroy');

    Route::post('task-lists/{taskList}/invitations', [InvitationController::class, 'store'])->name('task-lists.invitations.store');
    Route::delete('task-lists/{taskList}/invitations/{invitation}', [InvitationController::class, 'destroy'])->name('task-lists.invitations.destroy');
    Route::patch('invitations/{invitation}', [InvitationController::class, 'update'])->name('invitations.update');

    Route::patch('task-lists/{taskList}/tasks/{task}/assignee', [TaskAssignmentController::class, 'update'])->name('task-lists.tasks.assignee.update');
    Route::patch('task-lists/{taskList}/tasks/{task}/status', [TaskStatusController::class, 'update'])->name('task-lists.tasks.status.update');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{notification}', [NotificationController::class, 'update'])->name('notifications.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('users/{user}/status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
});
