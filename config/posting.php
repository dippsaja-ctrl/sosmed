<?php

/*
 * Konfigurasi platform & aturan konten.
 * PENTING: angka batas di bawah hanya contoh awal. Selalu cek dokumentasi resmi
 * terbaru tiap platform karena aturan bisa berubah.
 */
return [
    'graph_version' => env('GRAPH_VERSION', 'v21.0'),

    'platforms' => [
        'instagram' => [
            'label' => 'Instagram', 'short' => 'IG', 'color' => '#C2337A',
            'client_id' => env('INSTAGRAM_CLIENT_ID'),
            'client_secret' => env('INSTAGRAM_CLIENT_SECRET'),
            'types' => ['video' => 'Reels', 'carousel' => 'Carousel', 'photo' => 'Foto'],
            'caption_max' => 2200, 'photo_mb' => 8, 'video_mb' => 300,
            'video_sec' => [3, 90], 'slides' => [2, 10], 'ratio' => [0.8, 1.91],
            'jpeg_only' => true,
            'token_life' => 60 * 86400,
        ],
        'facebook' => [
            'label' => 'Facebook Page', 'short' => 'FB', 'color' => '#1D6FE0',
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'types' => ['video' => 'Video', 'carousel' => 'Multi-foto', 'photo' => 'Foto'],
            'caption_max' => 63000, 'photo_mb' => 10, 'video_mb' => 1024,
            'video_sec' => [1, 14400], 'slides' => [2, 10], 'ratio' => null,
            'token_life' => null, // Page token dari long-lived user token tidak kedaluwarsa
        ],
        'tiktok' => [
            'label' => 'TikTok', 'short' => 'TT', 'color' => '#0E2A33',
            'client_id' => env('TIKTOK_CLIENT_ID'),
            'client_secret' => env('TIKTOK_CLIENT_SECRET'),
            'types' => ['video' => 'Video', 'carousel' => 'Foto carousel'],
            'caption_max' => 2200, 'photo_mb' => 20, 'video_mb' => 500,
            'video_sec' => [3, 600], 'slides' => [2, 35], 'ratio' => null,
            // Sebelum audit disetujui, TikTok hanya mengizinkan SELF_ONLY (privat).
            'privacy' => env('TIKTOK_PRIVACY', 'SELF_ONLY'),
            'token_life' => 86400,
        ],
        'youtube' => [
            'label' => 'YouTube', 'short' => 'YT', 'color' => '#D93025',
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'types' => ['video' => 'Video'],
            'caption_max' => 5000, 'video_mb' => 2048,
            'video_sec' => [1, 43200], 'ratio' => null,
            'token_life' => 3600,
        ],
    ],
];
