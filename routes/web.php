<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialAccountController;
use Illuminate\Support\Facades\Route;
use Google\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/privacy-policy', function () {
    return view('privacy');
});

Route::get('/terms-of-service', function () {
    return view('terms');
});

Route::get('/auth/google', function () {
    $client = new Client();

    $client->setClientId(config('services.google.client_id'));
    $client->setClientSecret(config('services.google.client_secret'));
    $client->setRedirectUri(config('services.google.redirect'));

    $client->addScope('https://www.googleapis.com/auth/youtube.upload');

    return redirect()->away($client->createAuthUrl());
});

Route::get('/auth/google/callback', function () {
    $client = new Client();

    $client->setClientId(config('services.google.client_id'));
    $client->setClientSecret(config('services.google.client_secret'));
    $client->setRedirectUri(config('services.google.redirect'));

    if (request()->has('error')) {
        return 'Google OAuth dibatalkan: ' . request('error');
    }

    if (!request()->has('code')) {
        return 'Kode OAuth tidak ditemukan.';
    }

    $token = $client->fetchAccessTokenWithAuthCode(request('code'));

    if (isset($token['error'])) {
        return 'Gagal mendapatkan token: ' .
            ($token['error_description'] ?? $token['error']);
    }

    session(['google_token' => $token]);

    return 'Login Google berhasil! Token YouTube berhasil didapat.';
});

Route::get('/youtube/upload-test', function () {
    $token = session('google_token');

    if (!$token) {
        return 'Token Google tidak ditemukan. Hubungkan akun Google terlebih dahulu.';
    }

    $client = new Client();

    $client->setClientId(config('services.google.client_id'));
    $client->setClientSecret(config('services.google.client_secret'));
    $client->setRedirectUri(config('services.google.redirect'));
    $client->setAccessToken($token);

    $youtube = new \Google\Service\YouTube($client);

    $videoPath = storage_path('app/public/video-test.mp4');

    $video = new \Google\Service\YouTube\Video();

    $video->setSnippet(
        new \Google\Service\YouTube\VideoSnippet([
            'title' => 'Test Upload Multi Sosmed',
            'description' => 'Video test upload dari Laravel Multi-Sosmed Poster',
            'categoryId' => '22',
        ])
    );

    $video->setStatus(
        new \Google\Service\YouTube\VideoStatus([
            'privacyStatus' => 'private',
        ])
    );

    $response = $youtube->videos->insert(
        'snippet,status',
        $video,
        [
            'data' => file_get_contents($videoPath),
            'mimeType' => 'video/mp4',
            'uploadType' => 'multipart',
        ]
    );

    return 'Upload berhasil! Video ID: ' . $response->getId();
});

Route::get('/youtube/check-video', function () {
    $path = storage_path('app/public/video-test.mp4');

    if (file_exists($path)) {
        return 'Video ditemukan!';
    }

    return 'Video tidak ditemukan.';
});

// Sosial akun
Route::middleware('auth')->group(function () {
    Route::get('/social-accounts', [SocialAccountController::class, 'index'])->name('social-accounts.index');
    Route::get('/social-accounts/{platform}/redirect', [SocialAccountController::class, 'redirect'])->name('social-accounts.redirect');
    Route::delete('/social-accounts/{platform}', [SocialAccountController::class, 'destroy'])->name('social-accounts.destroy');
});

// Sistem Otentikasi bawaan Laravel
require __DIR__.'/auth.php';