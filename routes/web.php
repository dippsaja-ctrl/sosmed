<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SocialAccountController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schedule;

Route::get('/tiktok-test', function (Request $request) {
    $res = Http::asForm()->post('https://open.tiktokapis.com/v2/oauth/token/', [
        'client_key' => env('TIKTOK_CLIENT_ID'),
        'client_secret' => env('TIKTOK_CLIENT_SECRET'),
        'code' => $request->query('code'),
        'grant_type' => 'authorization_code',
        'redirect_uri' => 'https://depth-constrain-hangover.ngrok-free.dev/tiktok-test',
    ]);
    dd($res->json());
});
/*
|--------------------------------------------------------------------------
| Halaman Utama & Publik
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/dashboard')->name('welcome');

Route::get('/privacy-policy', [LegalController::class, 'privacy'])
    ->name('legal.privacy');

Route::get('/terms-of-service', [LegalController::class, 'terms'])
    ->name('legal.terms');

/*
|--------------------------------------------------------------------------
| OAuth Media Sosial (Koneksi Akun)
|--------------------------------------------------------------------------
*/

Route::get('/auth/{provider}', [OAuthController::class, 'redirect'])
    ->whereIn('provider', ['instagram', 'facebook', 'tiktok', 'youtube', 'google'])
    ->name('oauth.redirect');

Route::get('/auth/{provider}/callback', [OAuthController::class, 'callback'])
    ->whereIn('provider', ['instagram', 'facebook', 'tiktok', 'youtube', 'google'])
    ->name('oauth.callback');

/*
|--------------------------------------------------------------------------
| Social Login (Masuk dengan Akun Sosial via Socialite)
|--------------------------------------------------------------------------
*/

Route::prefix('social')->group(function () {
    Route::get('/{platform}/redirect', [SocialAuthController::class, 'redirect'])
        ->where('platform', 'google|facebook|github|twitter|tiktok')
        ->name('social.redirect');

    Route::get('/{platform}/callback', [SocialAuthController::class, 'callback'])
        ->where('platform', 'google|facebook|github|twitter|tiktok')
        ->name('social.callback');
});

/*
|--------------------------------------------------------------------------
| OTP Routes
|--------------------------------------------------------------------------
*/

Route::post('/otp/send', [OtpController::class, 'send'])
    ->middleware('throttle:6,1')
    ->name('otp.send');

Route::post('/otp/verify-register', [OtpController::class, 'verifyRegister'])
    ->middleware('throttle:10,1')
    ->name('otp.verifyRegister');

Route::post('/otp/reset-password', [OtpController::class, 'resetPassword'])
    ->middleware('throttle:10,1')
    ->name('otp.resetPassword');

/*
|--------------------------------------------------------------------------
| Halaman yang Memerlukan Login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

<<<<<<< HEAD
    /*
    | Dashboard
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    | Akun Media Sosial
    */
=======
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Akun Media Sosial
>>>>>>> 66d0ac889e53a75ebc4a07ce64df67b3065f4903
    Route::get('/accounts', [SocialAccountController::class, 'index'])
        ->name('accounts.index');

    Route::post('/accounts/{platform}/refresh', [SocialAccountController::class, 'refresh'])
        ->name('accounts.refresh');

    Route::delete('/accounts/{socialAccount}', [SocialAccountController::class, 'destroy'])
        ->name('accounts.destroy');

<<<<<<< HEAD
    /*
    | Posting
    */
=======
    // Posting
>>>>>>> 66d0ac889e53a75ebc4a07ce64df67b3065f4903
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

<<<<<<< HEAD
    /*
    | Jadwal Posting
    */
    Route::get('/schedule', [ScheduleController::class, 'index'])
        ->name('schedule.index');

    /*
    | Riwayat Posting
    */
=======
    // Jadwal Posting
    Route::get('/schedule', [ScheduleController::class, 'index'])
        ->name('schedule.index');

    // Riwayat Posting
>>>>>>> 66d0ac889e53a75ebc4a07ce64df67b3065f4903
    Route::get('/history', [HistoryController::class, 'index'])
        ->name('history.index');

    Route::post('/history/{post}/retry/{platform}', [HistoryController::class, 'retry'])
        ->name('history.retry');

    Route::post('/history/{post}/retry-all', [HistoryController::class, 'retryAll'])
        ->name('history.retry-all');

<<<<<<< HEAD
    /*
    | Notifikasi
    */
    Route::post('/notifikasi/baca', function () {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    })->name('notifications.read');

    /*
    | Profil
    */
=======
    // Profil
>>>>>>> 66d0ac889e53a75ebc4a07ce64df67b3065f4903
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Hapus foto profil
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])
        ->name('profile.photo.delete');

    // Hapus akun pengguna
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

<<<<<<< HEAD
Schedule::command('sosmed:clean-temp --hours=24')->hourly();
=======
/*
|--------------------------------------------------------------------------
| Route Autentikasi Laravel
|--------------------------------------------------------------------------
*/
>>>>>>> 66d0ac889e53a75ebc4a07ce64df67b3065f4903

require __DIR__.'/auth.php';
