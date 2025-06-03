<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/map', function () {
    return view('map');
})->name('map');

Route::get('/parkless-login', [AuthenticatedSessionController::class, 'create'])->name('parklessLogin');
Route::post('/parkless-login', [AuthenticatedSessionController::class, 'store']);
Route::get('/review/{id}', [ReviewController::class, 'show']);

Route::middleware(['auth'])->group(function () {
    Route::post('/review', [ReviewController::class, 'store']);
    Route::post('/review/{reviewId}/vote', [ReviewController::class, 'vote']);

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile/edit', function () {
        return view('profile.edit');
    })->name('profile.edit');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

require __DIR__ . '/auth.php';
