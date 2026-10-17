<?php

namespace App\Services\Platforms;

class PlatformManager
{
    public function for(string $key): Platform
    {
        return match ($key) {
            'instagram' => new InstagramPlatform(),
            'facebook'  => new FacebookPlatform(),
            'tiktok'    => new TikTokPlatform(),
            'youtube'   => new YouTubePlatform(),
            default     => abort(404, 'Platform tidak dikenal.'),
        };
    }
}
