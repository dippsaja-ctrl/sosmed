<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialAccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini adalah tempat untuk mendaftarkan semua route aplikasi Sosmed.
|
*/

// Halaman utama / landing page bawaan Laravel
Route::get('/', function () {
        return view('welcome');
});

// Halaman Dashboard dan Profile (Hanya bisa dibuka jika SUDAH login)
Route::get('/dashboard', function () {
        return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
                Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Halaman Legalitas Kebutuhan Pendaftaran API (Tugas Orang Ke-3)
|--------------------------------------------------------------------------
| Halaman ini ditaruh di luar middleware 'auth' agar pihak Meta, TikTok, 
| dan Google bisa memeriksa dan mengakses link ini secara publik tanpa login.
|
*/

// Halaman Kebijakan Privasi (Privacy Policy)
Route::get('/privacy-policy', function () {
        return view('privacy');
});

// Halaman Syarat Layanan (Terms of Service)
Route::get('/terms-of-service', function () {
        return view('terms');
});

// sosial akun
Route::middleware('auth')->group(function () {
    Route::get('/social-accounts', [SocialAccountController::class, 'index'])->name('social-accounts.index');
    Route::get('/social-accounts/{platform}/redirect', [SocialAccountController::class, 'redirect'])->name('social-accounts.redirect');
    Route::delete('/social-accounts/{platform}', [SocialAccountController::class, 'destroy'])->name('social-accounts.destroy');
});

// Sistem Otentikasi bawaan Laravel (Login, Register, Logout, dll)
require __DIR__.'/auth.php';
