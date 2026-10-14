<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class TikTokController extends Controller
{
    // Fungsi 1: Melempar user ke halaman izin login TikTok
    public function redirectToProvider()
    {
        return Socialite::driver('tiktok')
            ->scopes(['user.info.basic', 'video.publish', 'video.upload']) // Scope sesuai dashboard kamu
            ->redirect();
    }

    // Fungsi 2: Menangkap data dan menampilkan Access Token di layar browser [source: 1.3.6]
    public function handleProviderCallback()
    {
        // Laravel Socialite otomatis menukarkan kode callback menjadi token resmi [source: 1.1.4, 1.1.5]
        $tiktokUser = Socialite::driver('tiktok')->user();
        
        // Sengaja di-dump (dd) agar tokennya langsung muncul di layar buat dicatat [source: 1.3.6]
        dd([
            'PESAN' => 'Token Berhasil Didapatkan!',
            'ACCESS_TOKEN_KAMU' => $tiktokUser->token,
            'REFRESH_TOKEN_KAMU' => $tiktokUser->refreshToken
        ]);
    }
}