<?php

namespace App\Services\Publishers;

use App\Models\Post;
use App\Models\SocialAccount;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Facades\Http;

class YouTubePublisher implements PlatformPublisher
{
    public function publish(Post $post, string $token, SocialAccount $account): string
    {
        $video = $post->media->first();
        $path  = $video->fullPath();
        $size  = filesize($path);

        // 1. Mulai sesi resumable upload
        $init = Http::withToken($token)
            ->withHeaders([
                'X-Upload-Content-Type'   => $video->mime,
                'X-Upload-Content-Length' => $size,
            ])
            ->post('https://www.googleapis.com/upload/youtube/v3/videos?uploadType=resumable&part=snippet,status', [
                'snippet' => ['title' => $post->title, 'description' => $post->caption ?? ''],
                // Project API yang belum diaudit Google dipaksa private
                'status'  => ['privacyStatus' => 'private'],
            ])
            ->throw();

        $uploadUrl = $init->header('Location');

        // 2. Kirim file sebagai stream (hemat memori)
        $handle = fopen($path, 'r');

        $res = Http::withToken($token)
            ->timeout(0)
            ->withBody(Utils::streamFor($handle), $video->mime)
            ->put($uploadUrl)
            ->throw();

        return $res->json('id');
    }
}