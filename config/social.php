<?php

return [
    'graph_version' => 'v21.0',

    // platform tujuan => provider OAuth yang menyimpan tokennya + class publisher
    'platforms' => [
        'youtube'   => ['provider' => 'google',   'publisher' => \App\Services\Publishers\YouTubePublisher::class],
        'tiktok'    => ['provider' => 'tiktok',   'publisher' => \App\Services\Publishers\TikTokPublisher::class],
        'instagram' => ['provider' => 'facebook', 'publisher' => \App\Services\Publishers\InstagramPublisher::class],
    ],
];