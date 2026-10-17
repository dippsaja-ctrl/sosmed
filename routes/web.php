<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SocialAccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');})->name('welcome');

// Halaman publik (banyak platform mewajibkan link ini saat mendaftar developer app)
Route::get('/kebijakan-privasi', [LegalController::class, 'show'])->defaults('page', 'privacy')->name('legal.privacy');
Route::get('/syarat-layanan', [LegalController::class, 'show'])->defaults('page', 'terms')->name('legal.terms');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Akun sosmed + OAuth
    Route::get('/akun-sosmed', [SocialAccountController::class, 'index'])->name('accounts.index');
    Route::post('/akun-sosmed/{platform}/refresh', [SocialAccountController::class, 'refresh'])->name('accounts.refresh');
    Route::delete('/akun-sosmed/{platform}', [SocialAccountController::class, 'destroy'])->name('accounts.destroy');
    Route::get('/auth/{platform}/redirect', [OAuthController::class, 'redirect'])->name('oauth.redirect');
    Route::get('/auth/{platform}/callback', [OAuthController::class, 'callback'])->name('oauth.callback');

    // Posting
    Route::get('/posting', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posting', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posting/{post}/pratinjau', [PostController::class, 'preview'])->name('posts.preview');
    Route::post('/posting/{post}/konfirmasi', [PostController::class, 'confirm'])->name('posts.confirm');
    Route::delete('/posting/{post}', [PostController::class, 'discard'])->name('posts.discard');
    Route::get('/posting/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posting/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::post('/posting/{post}/batal', [PostController::class, 'cancel'])->name('posts.cancel');

    // Jadwal & riwayat
    Route::get('/terjadwal', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/riwayat', [HistoryController::class, 'index'])->name('history.index');
    Route::post('/riwayat/{post}/retry', [HistoryController::class, 'retryAll'])->name('history.retry-all');
    Route::post('/riwayat/{post}/retry/{platform}', [HistoryController::class, 'retry'])->name('history.retry');

    // Notifikasi (lonceng)
    Route::post('/notifikasi/baca', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read');

    // Profil bawaan Laravel Breeze (dibutuhkan oleh layout/navigasi Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
