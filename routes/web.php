
<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SocialAccountController;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Route;

<<<<<<< HEAD
Route::redirect('/', '/dashboard');

Route::get('/', function () {
    return redirect()->route('login');})->name('welcome');
=======
/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/
>>>>>>> 645607949a780e1c0ec292546c23efdc46250fff

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
<<<<<<< HEAD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Akun sosmed + OAuth
    Route::get('/akun-sosmed', [SocialAccountController::class, 'index'])->name('accounts.index');
    Route::post('/akun-sosmed/{platform}/refresh', [SocialAccountController::class, 'refresh'])->name('accounts.refresh');
    Route::delete('/akun-sosmed/{platform}', [SocialAccountController::class, 'destroy'])->name('accounts.destroy');
    Route::get('/auth/{platform}/redirect', [OAuthController::class, 'redirect'])->name('oauth.redirect');
    Route::get('/auth/{platform}/callback', [OAuthController::class, 'callback'])->name('oauth.callback');
=======

    /*
    | Dashboard
    */
>>>>>>> 645607949a780e1c0ec292546c23efdc46250fff

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

<<<<<<< HEAD
    // Posting
    Route::get('/posting', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posting', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posting/{post}/pratinjau', [PostController::class, 'preview'])->name('posts.preview');
    Route::post('/posting/{post}/konfirmasi', [PostController::class, 'confirm'])->name('posts.confirm');
    Route::delete('/posting/{post}', [PostController::class, 'discard'])->name('posts.discard');
    Route::get('/posting/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posting/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::post('/posting/{post}/batal', [PostController::class, 'cancel'])->name('posts.cancel');
=======
    /*
    | Akun Media Sosial
    */
>>>>>>> 645607949a780e1c0ec292546c23efdc46250fff

    Route::get('/accounts', [SocialAccountController::class, 'index'])
        ->name('accounts.index');

    Route::delete('/accounts/{socialAccount}', [SocialAccountController::class, 'destroy'])
        ->name('accounts.destroy');

<<<<<<< HEAD
    Route::get('/terjadwal', function () {
        return view('terjadwal');
    })->name('terjadwal');
=======
    /*
    | Posting
    */
>>>>>>> 645607949a780e1c0ec292546c23efdc46250fff

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

<<<<<<< HEAD
Route::get('/auth/tiktok/redirect', [TikTokController::class, 'redirectToProvider']);
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

Route::middleware('auth')->group(function () {
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
});

Route::prefix('auth')->group(function () {
    Route::get('/{platform}/redirect', [SocialAuthController::class, 'redirect'])
        ->where('platform', 'google|facebook|github|twitter')
        ->name('social.redirect');

    Route::get('/{platform}/callback', [SocialAuthController::class, 'callback'])
        ->where('platform', 'google|facebook|github|twitter')
        ->name('social.callback');
});

Route::post('/otp/send', [OtpController::class, 'send'])
    ->middleware('throttle:6,1');
Route::post('/otp/verify-register', [OtpController::class, 'verifyRegister'])
    ->middleware('throttle:10,1');
Route::post('/otp/reset-password', [OtpController::class, 'resetPassword'])
    ->middleware('throttle:10,1');

Route::post('/history/{target}/retry', [HistoryController::class, 'retry'])->name('history.retry');

Route::post('/history/{post}/retry/{platform}', [HistoryController::class, 'retry'])->name('history.retry');
Route::post('/history/{post}/retry-all', [HistoryController::class, 'retryAll'])->name('history.retryAll');

Schedule::command('sosmed:clean-temp --hours=24')->hourly();

=======
>>>>>>> 645607949a780e1c0ec292546c23efdc46250fff
require __DIR__.'/auth.php';
