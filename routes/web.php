<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/map', function () {
    return view('map');
})->name('map');

Route::get('/review/{id}', [ReviewController::class, 'show']);

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
