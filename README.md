# Pos Bersama: kode Laravel (sistem + tampilan)

Kode ini adalah versi Laravel dari prototype Pos Bersama. Isinya adalah **file yang ditimpa ke project Laravel baru**
(bukan project lengkap), supaya Anda tetap menjalankan `composer create-project` sendiri
sesuai Tahap 0 di modul.

Asumsi: **Laravel 11 atau 12**, PHP 8.2+, starter kit **Laravel Breeze (Blade)** untuk login/register.

> Penting: kode ini ditulis dan diperiksa kurungnya, tetapi **belum dijalankan** terhadap API Instagram/Facebook/TikTok/YouTube
> yang sebenarnya (tidak ada akses internet saat pembuatan). Endpoint dan parameter bisa berubah, jadi cocokkan
> dengan dokumentasi resmi tiap platform dan uji satu per satu seperti Tahap 2 di modul.

## 1. Pasang project dasar

```bash
composer create-project laravel/laravel posbersama
cd posbersama
composer require laravel/breeze --dev
php artisan breeze:install blade     # pilih dark mode: no, testing: sesuai selera
```

Atur database di `.env` (SQLite bawaan juga boleh).

## 2. Salin file dari paket ini

Salin isi folder paket ini ke root project (timpa kalau ditanya):

| Folder/file paket | Tujuan |
|---|---|
| `app/` | Model, controller, job, service platform, command, validator |
| `config/posting.php` | Konfigurasi platform dan aturan konten |
| `database/migrations/` | Tabel `social_accounts`, `posts`, `post_targets`, `notifications` |
| `resources/views/` | Semua tampilan Blade |
| `public/css/posbersama.css` | Gaya tampilan |
| `routes/web.php`, `routes/console.php` | Route dan jadwal scheduler (**menimpa** bawaan Breeze, route profil dan auth tetap dipertahankan) |
| `.env.additions` | Variabel yang ditambahkan ke `.env` |

Edit `config/app.php`: ubah `'timezone' => 'UTC'` menjadi `'Asia/Jakarta'` (atau zona waktu kantor).
Jadwal posting dibaca memakai zona waktu ini.

Lalu jalankan:

```bash
php artisan migrate
php artisan storage:link
php artisan config:clear
```

Kalau Anda sudah pernah menjalankan `php artisan make:notifications-table`, hapus file migration
`..._create_notifications_table.php` dari paket ini supaya tidak bentrok.

## 3. Daftarkan developer app (Tahap 0)

Redirect URI tiap platform harus **sama persis** dengan yang dibuat Laravel:

```
{APP_URL}/auth/instagram/callback
{APP_URL}/auth/facebook/callback
{APP_URL}/auth/tiktok/callback
{APP_URL}/auth/youtube/callback
```

| Platform | Dashboard | Izin yang dipakai |
|---|---|---|
| Instagram | developers.facebook.com (produk Instagram API with Instagram Login) | `instagram_business_basic`, `instagram_business_content_publish` |
| Facebook Page | developers.facebook.com (Facebook Login) | `pages_show_list`, `pages_manage_posts`, `pages_read_engagement` |
| TikTok | developers.tiktok.com (Login Kit + Content Posting API) | `user.info.basic`, `video.publish` |
| YouTube | console.cloud.google.com (YouTube Data API v3 + OAuth Client ID) | `youtube.upload`, `youtube.readonly` |

Isi Client ID/Secret di `.env` (lihat `.env.additions`). Setelah mengubah `.env`, jalankan `php artisan config:clear`.
Halaman Kebijakan Privasi dan Syarat Layanan ada di `/kebijakan-privasi` dan `/syarat-layanan`.

## 4. Kenapa APP_URL harus publik

Instagram, Facebook (foto/video) dan TikTok (foto) **mengambil media lewat URL**. Jadi file di
`storage/app/public` harus bisa dibuka dari internet. Saat development gunakan tunnel
(ngrok / Cloudflare Tunnel), lalu isi `APP_URL` dengan alamat HTTPS tunnel itu dan daftarkan redirect URI memakai alamat yang sama.
TikTok juga mewajibkan prefix URL/domain media diverifikasi di dashboard developer untuk mode `PULL_FROM_URL`.
Upload video TikTok dan YouTube memakai upload langsung dari server, jadi tidak butuh URL publik.

Naikkan batas upload PHP di `php.ini` (`upload_max_filesize`, `post_max_size`) kalau memposting video besar.

## 5. Menjalankan

Buka 3 terminal:

```bash
php artisan serve              # aplikasi web
php artisan queue:work         # worker yang benar-benar mengirim posting
php artisan schedule:work      # scheduler untuk development (menjalankan cek tiap menit)
```

Di server produksi, ganti `schedule:work` dengan satu entri cron:

```
* * * * * cd /path/ke/project && php artisan schedule:run >> /dev/null 2>&1
```

dan jalankan `queue:work` lewat Supervisor agar tidak mati.

Perintah manual untuk uji coba:

```bash
php artisan posts:dispatch-due     # proses jadwal yang sudah jatuh tempo
php artisan tokens:refresh         # perbarui token yang hampir habis
```

## 6. Alur kode (supaya mudah dijelaskan ke pembimbing)

1. **Hubungkan akun**: `OAuthController@redirect` mengirim ke halaman platform, lalu `@callback` menukar `code` dengan token
   lewat `Platform::exchangeCode()` dan menyimpannya di `SocialAccount` (terenkripsi lewat cast `encrypted`).
2. **Buat posting**: `PostController@store` memvalidasi bentuk request, lalu `PostValidator` memeriksa aturan tiap platform
   (rasio, ukuran, durasi, jumlah slide, panjang caption). Kalau lolos, file disimpan dan posting dibuat berstatus **draf**.
3. **Pratinjau**: `posts.preview`. Tombol konfirmasi memilih jalur:
   - Posting sekarang: satu `PublishToPlatform` job per platform masuk antrean.
   - Jadwalkan: status `scheduled`, menunggu scheduler.
4. **Scheduler**: tiap menit `posts:dispatch-due` mengklaim posting jatuh tempo secara atomik, lalu mengantre job (3 percobaan,
   jeda 5 dan 15 menit). `tokens:refresh` memperbarui token yang hampir habis.
5. **Job**: `PublishToPlatform` memanggil `InstagramPlatform`/`FacebookPlatform`/`TikTokPlatform`/`YouTubePlatform::publish()`.
   Hasil dan error tersimpan di `post_targets`. Saat semua platform selesai, pengguna menerima notifikasi (lonceng).
6. **Riwayat**: `HistoryController` menampilkan status per platform dan tombol **Coba lagi**.

Peta ke tahap modul: Tahap 1 (login, dashboard, migration) → Tahap 3 (OAuth, refresh token) → Tahap 4 (form, validasi, pratinjau)
→ Tahap 5 (scheduler, queue, retry, notifikasi) → Tahap 6 (riwayat, coba lagi).

## 7. Uji coba bertahap (Tahap 2 modul)

Sebelum memakai UI, uji satu platform lewat Tinker setelah akun terhubung:

```php
php artisan tinker
>>> $a = App\Models\SocialAccount::where('platform','instagram')->first();
>>> $post = App\Models\Post::first();
>>> app(App\Services\Platforms\PlatformManager::class)->for('instagram')->publish($post, $a);
```

## 8. Catatan keamanan dan batasan

- Jangan commit `.env`. Token di database terenkripsi memakai `APP_KEY`; kalau `APP_KEY` berubah, token lama tidak bisa dibaca dan akun harus dihubungkan ulang.
- Postingan TikTok dan YouTube tetap **privat** sampai audit platform disetujui (urusan kantor, bukan syarat kelulusan PKL).
- Instagram hanya menerima gambar **JPEG** untuk foto/carousel (sudah divalidasi).
- Aturan batas di `config/posting.php` hanya contoh. Cek dokumentasi resmi terbaru.
- Satu akun per platform per pengguna (unik di database). Facebook memakai Page pertama yang dikelola akun tersebut.
- Untuk Laravel 10: pindahkan isi `routes/console.php` ke `app/Console/Kernel.php` (`schedule()`), dan daftarkan command secara manual bila perlu.
- Belum ada tes otomatis. Menambahkan Feature Test untuk `PostValidator` adalah latihan yang bagus.
