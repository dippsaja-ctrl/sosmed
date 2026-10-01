<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $provider)
    {
        return match ($provider) {
            'facebook' => Socialite::driver('facebook')
                ->scopes(['pages_show_list', 'instagram_business_basic', 'instagram_business_content_publish'])
                ->redirect(),

            'google' => Socialite::driver('google')
                ->scopes(['https://www.googleapis.com/auth/youtube.upload', 'https://www.googleapis.com/auth/youtube.readonly'])
                ->redirect(),

            'tiktok' => Socialite::driver('tiktok')
                ->scopes(['user.info.basic', 'video.publish'])
                ->redirect(),
        };
    }

    public function callback(string $provider)
    {
        // method ini kita isi nanti, waktu bagian "simpan token" dikerjakan
    }
}