<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/map', function () {
    return view('map');
})->name('map');

Route::get('/parkless-login', function () {
    return view('auth.parklessLogin');
})->name('parklessLogin');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile/edit', function () {
        return view('profile.edit');
    })->name('profile.edit');
});

require __DIR__ . '/auth.php';
