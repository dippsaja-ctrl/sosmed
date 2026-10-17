
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

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/dashboard')->name('welcome');

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/

Route::get('/privacy-policy', [LegalController::class, 'privacy'])
    ->name('legal.privacy');

Route::get('/terms-of-service', [LegalController::class, 'terms'])
    ->name('legal.terms');

/*
|--------------------------------------------------------------------------
| OAuth Media Sosial
|--------------------------------------------------------------------------
| Sesuaikan method dengan OAuthController yang ada di proyek.
*/

Route::get('/auth/{provider}', [OAuthController::class, 'redirect'])
    ->whereIn('provider', ['google', 'facebook', 'tiktok'])
    ->name('oauth.redirect');

Route::get('/auth/{provider}/callback', [OAuthController::class, 'callback'])
    ->whereIn('provider', ['google', 'facebook', 'tiktok'])
    ->name('oauth.callback');

/*
|--------------------------------------------------------------------------
| Halaman yang Memerlukan Login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    | Dashboard
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    | Akun Media Sosial
    */

    Route::get('/accounts', [SocialAccountController::class, 'index'])
        ->name('accounts.index');

    Route::delete('/accounts/{socialAccount}', [SocialAccountController::class, 'destroy'])
        ->name('accounts.destroy');

    /*
    | Posting
    */

    Route::get('/posts/create', [PostController::class, 'create'])
        ->name('posts.create');

    Route::post('/posts', [PostController::class, 'store'])
        ->name('posts.store');

    Route::get('/posts/{post}/preview', [PostController::class, 'preview'])
        ->name('posts.preview');

    Route::post('/posts/{post}/confirm', [PostController::class, 'confirm'])
        ->name('posts.confirm');

    Route::delete('/posts/{post}/discard', [PostController::class, 'discard'])
        ->name('posts.discard');

    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
        ->name('posts.edit');

    Route::put('/posts/{post}', [PostController::class, 'update'])
        ->name('posts.update');

    Route::post('/posts/{post}/cancel', [PostController::class, 'cancel'])
        ->name('posts.cancel');

    /*
    | Jadwal Posting
    */

    Route::get('/schedule', [ScheduleController::class, 'index'])
        ->name('schedule.index');

    /*
    | Riwayat Posting
    */

    Route::get('/history', [HistoryController::class, 'index'])
        ->name('history.index');

    Route::post('/history/{post}/retry/{platform}', [HistoryController::class, 'retry'])
        ->name('history.retry');

    Route::post('/history/{post}/retry-all', [HistoryController::class, 'retryAll'])
        ->name('history.retry-all');

    /*
    | Profil
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Route Autentikasi Laravel
|--------------------------------------------------------------------------
| File ini biasanya menyediakan login, register, logout,
| dan reset password jika proyek menggunakan Laravel Breeze.
*/

require __DIR__.'/auth.php';
