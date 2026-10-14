<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\TikTokController;
use App\Http\Controllers\SocialAccountController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Google\Client;

Route::get('/', fn () => view('auth.welcome'))->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Login sosial (opsional, butuh Laravel Socialite)
    Route::get('/auth/{provider}', [SocialController::class, 'redirect'])
        ->whereIn('provider', ['google', 'facebook', 'tiktok'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialController::class, 'callback'])
        ->whereIn('provider', ['google', 'facebook', 'tiktok'])->name('social.callback');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))
        ->name('dashboard');

    Route::get('/posting', function () {
        return view('posting');
    })->name('posting');

    Route::get('/terjadwal', function () {
        return view('terjadwal');
    })->name('terjadwal');

    Route::get('/akun', function () {
        return view('akun');
    })->name('akun');

    Route::get('/riwayat', function () {
        return view('riwayat');
    })->name('riwayat');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});


Route::get('/auth/tiktok/redirect', [TikTokController::class, 'redirectToProvider']);

Route::get('/auth/tiktok/callback', [TikTokController::class, 'handleProviderCallback']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Privacy & Terms
|--------------------------------------------------------------------------
*/

Route::get('/privacy-policy', function () {
    return view('privacy');
});

Route::get('/terms-of-service', function () {
    return view('terms');
});

/*
|--------------------------------------------------------------------------
| GOOGLE / YOUTUBE (TETAP UTUH & AMAN - JANGAN DIGANGGU)
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', function () {
    $client = new Client();

    $client->setClientId(config('services.google.client_id'));
    $client->setClientSecret(config('services.google.client_secret'));
    $client->setRedirectUri(config('services.google.redirect'));

    $client->addScope(
        'https://googleapis.com'
    );

    return redirect()->away(
        $client->createAuthUrl()
    );
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
        return 'Kode OAuth Google tidak ditemukan.';
    }

    $token = $client->fetchAccessTokenWithAuthCode(
        request('code')
    );

    if (isset($token['error'])) {
        return 'Gagal mendapatkan token Google: ' .
            ($token['error_description'] ?? $token['error']);
    }

    session([
        'google_token' => $token
    ]);

    return 'Login Google berhasil! Token YouTube berhasil didapat.';
});

/*
|--------------------------------------------------------------------------
| YOUTUBE UPLOAD VIA QUEUE (UPDATED)
|--------------------------------------------------------------------------
*/

Route::get('/youtube/upload-test', function () {
    $token = session('google_token');

    if (!$token) {
        return 'Token Google tidak ditemukan. Hubungkan akun Google terlebih dahulu.';
    }

    $videoPath = storage_path('app/public/video-test.mp4');

    if (!file_exists($videoPath)) {
        return 'Video tidak ditemukan di: ' . $videoPath;
    }

    // Memicu job untuk berjalan di antrean latar belakang
    \App\Jobs\UploadYouTubeJob::dispatch($videoPath, 'Test Upload Via Queue', $token);

    return 'Job upload sudah dimasukkan ke dalam antrean (Queue)! Silakan cek terminal queue:work atau file log.';
});

/*
|--------------------------------------------------------------------------
| FACEBOOK LOGIN (FIXED MENGGUNAKAN VERSI API v26.0 RESMI META)
|--------------------------------------------------------------------------
*/

Route::get('/auth/facebook', function () {
    // Generate secure state token 
    $state = bin2hex(random_bytes(16));

    // Simpan ke session internal
    session(['meta_state' => $state]);
    session()->save();

    $params = http_build_query([
        'client_id' => config('services.meta.client_id'),
        'redirect_uri' => config('services.meta.redirect'),
        'state' => $state,
        'scope' => 'public_profile', 
        'response_type' => 'code',
    ]);

    // Dialihkan menggunakan Meta Graph API Endpoint v26.0 terbaru
    return redirect()->away(
        'https://facebook.com?' . $params
    );
});

Route::get('/auth/facebook/callback', function () {
    if (request()->has('error')) {
        return 'Login Facebook dibatalkan: ' . request('error_description') . ' (' . request('error') . ')';
    }

    if (!request()->has('code')) {
        return 'Kode otentikasi Facebook tidak ditemukan.';
    }

    $savedState = session('meta_state');
    $returnedState = request('state');

    // Validasi Keamanan State token anti CSRF
    if (!$savedState || !$returnedState || !hash_equals($savedState, $returnedState)) {
        return response()->json([
            'error' => 'State OAuth Facebook tidak valid.',
            'solusi' => 'Pastikan Anda membuka web menggunakan domain Ngrok HTTPS sejak awal, bukan localhost.',
            'debug' => [
                'session_saved_state' => $savedState,
                'request_returned_state' => $returnedState
            ]
        ], 403);
    }

    // Bersihkan state agar tidak bisa direply
    session()->forget('meta_state');

    // Tukar code menjadi Access Token resmi melalui Meta Graph API v26.0
    $response = Http::get(
        'https://facebook.com',
        [
            'client_id' => config('services.meta.client_id'),
            'client_secret' => config('services.meta.client_secret'),
            'redirect_uri' => config('services.meta.redirect'),
            'code' => request('code'),
        ]
    );

    if ($response->failed()) {
        return response()->json([
            'error' => 'Gagal menukarkan token di endpoint Meta Graph API.',
            'details' => $response->json()
        ], $response->status());
    }

    $data = $response->json();

    if (!isset($data['access_token'])) {
        return 'Access Token tidak ditemukan pada response payload Meta.';
    }

    // Simpan access token ke session
    session(['facebook_token' => $data]);
    session()->save();

    // Mengambil profil data dasar untuk memastikan token berjalan 100%
    $userProfile = Http::get('https://facebook.com', [
        'fields' => 'id,name,picture',
        'access_token' => $data['access_token']
    ])->json();

    return response()->json([
        'status' => 'success',
        'message' => 'Login Facebook berhasil terintegrasi penuh!',
        'user_details' => $userProfile,
        'token_preview' => substr($data['access_token'], 0, 15) . '...'
    ]);
});

/*
|--------------------------------------------------------------------------
| ENDPOINT TESTING FACEBOOK TOKEN
|--------------------------------------------------------------------------
*/

Route::get('/facebook/test-token', function () {
    $tokenData = session('facebook_token');

    if (!$tokenData || !isset($tokenData['access_token'])) {
        return response()->json([
            'status' => 'error',
            'message' => 'Access token tidak terdeteksi di session. Lakukan login ulang di /auth/facebook'
        ], 404);
    }

    // Hit langsung ke Meta Graph API v26.0
    $response = Http::get('https://facebook.com', [
        'fields' => 'id,name,picture',
        'access_token' => $tokenData['access_token'],
    ]);

    if ($response->failed()) {
        return response()->json([
            'status' => 'Token kedaluwarsa atau corrupt',
            'meta_error' => $response->json()
        ], $response->status());
    }

    return response()->json([
        'status' => 'Token Session Aktif & Valid!',
        'profile_data' => $response->json()
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/connect/{platform}', [SocialAccountController::class, 'redirect']);
    Route::get('/connect/{platform}/callback', [SocialAccountController::class, 'callback']);
});

/*
|--------------------------------------------------------------------------
| Sistem Otentikasi bawaan Laravel
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
