<?php

use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function (): void {
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
});
