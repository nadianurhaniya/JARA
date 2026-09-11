<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('jara')->name('jara.')->group(function () {
    Route::get('/', function () {
        return view('jara.app');
    })->name('app');

    Route::get('/members', function () {
        return view('jara.app', ['tab' => 'members']);
    })->name('members');

    Route::get('/invitations', function () {
        return view('jara.app', ['tab' => 'invitations']);
    })->name('invitations');

    Route::get('/tasks', function () {
        return view('jara.app', ['tab' => 'tasks']);
    })->name('tasks');
});
