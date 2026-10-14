# Auth Peaks (Laravel) — Welcome, Login, Register

## Instalasi
1. Buat project (Laravel 11/12):
   composer create-project laravel/laravel peaks
2. Atur database di `.env`, lalu salin semua file dari folder ini ke project
   (timpa `routes/web.php`; folder `app`, `resources`, `public`, `database` digabung).
3. (Opsional, hanya untuk login sosial)
   composer require laravel/socialite
   composer require socialiteproviders/tiktok   # untuk TikTok
4. Tambahkan di `app/Models/User.php` pada `$fillable`:
   'provider_name', 'provider_id'
5. Jalankan:
   php artisan migrate
   php artisan serve
6. Buka http://127.0.0.1:8000

## Konfigurasi login sosial (opsional)
Tambahkan di `config/services.php`:

    'google'   => ['client_id' => env('GOOGLE_CLIENT_ID'),   'client_secret' => env('GOOGLE_CLIENT_SECRET'),   'redirect' => env('GOOGLE_REDIRECT_URI')],
    'facebook' => ['client_id' => env('FACEBOOK_CLIENT_ID'), 'client_secret' => env('FACEBOOK_CLIENT_SECRET'), 'redirect' => env('FACEBOOK_REDIRECT_URI')],
    'tiktok'   => ['client_id' => env('TIKTOK_CLIENT_ID'),   'client_secret' => env('TIKTOK_CLIENT_SECRET'),   'redirect' => env('TIKTOK_REDIRECT_URI')],

Redirect URI: http://127.0.0.1:8000/auth/{google|facebook|tiktok}/callback
Untuk TikTok, daftarkan juga event listener SocialiteProviders di `AppServiceProvider`.
Jika belum butuh login sosial, hapus `@include('auth._social')` dan route `/auth/...`.
