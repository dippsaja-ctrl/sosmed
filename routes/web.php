<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\TikTokController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('auth.welcome'))->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login sosial (opsional, butuh Laravel Socialite)
    Route::get('/auth/{provider}', [SocialController::class, 'redirect'])
        ->whereIn('provider', ['google', 'facebook', 'tiktok'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialController::class, 'callback'])
        ->whereIn('provider', ['google', 'facebook', 'tiktok'])->name('social.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/auth/tiktok/redirect', [TikTokController::class, 'redirectToProvider']);

Route::get('/auth/tiktok/callback', [TikTokController::class, 'handleProviderCallback']);
