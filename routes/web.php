<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('jara.app');
})->name('home');

Route::get('/jara', function () {
    return view('jara.app');
})->name('jara.app');
