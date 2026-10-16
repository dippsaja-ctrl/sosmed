<?php

namespace App\Services\Publishers;

use App\Models\Post;
use App\Models\SocialAccount;

interface PlatformPublisher
{
    /** @return string ID postingan di platform tujuan. Lempar exception jika gagal. */
    public function publish(Post $post, string $token, SocialAccount $account): string;
}