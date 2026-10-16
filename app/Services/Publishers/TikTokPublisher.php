<?php

namespace App\Services\Publishers;

use App\Models\Post;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class TikTokPublisher implements PlatformPublisher
{
    private const CHUNK = 10 * 1024 * 1024; // 10 MB

    public function publish(Post $post, string $token, SocialAccount $account): string
    {
        $video = $post->media->first();
        $path  = $video->fullPath();
        $size  = filesize($path);

        $chunkSize  = min($size, self::CHUNK);
        $chunkCount = max(1, intdiv($size, $chunkSize)); // chunk terakhir boleh lebih besar

        $init = Http::withToken($token)
            ->post('https://open.tiktokapis.com/v2/post/publish/video/init/', [
                'post_info' => [
                    'title'         => $post->caption ?? $post->title ?? '',
                    // App yang belum diaudit TikTok hanya boleh SELF_ONLY
                    'privacy_level' => 'SELF_ONLY',
                ],
                'source_info' => [
                    'source'            => 'FILE_UPLOAD',
                    'video_size'        => $size,
                    'chunk_size'        => $chunkSize,
                    'total_chunk_count' => $chunkCount,
                ],
            ])
            ->throw();

        if ($init->json('error.code') !== 'ok') {
            throw new RuntimeException('TikTok init gagal: ' . $init->json('error.message'));
        }

        $uploadUrl = $init->json('data.upload_url');
        $handle    = fopen($path, 'r');

        for ($i = 0; $i < $chunkCount; $i++) {
            $start = $i * $chunkSize;
            $end   = ($i === $chunkCount - 1) ? $size - 1 : $start + $chunkSize - 1;

            fseek($handle, $start);
            $data = fread($handle, $end - $start + 1);

            Http::withHeaders(['Content-Range' => "bytes {$start}-{$end}/{$size}"])
                ->withBody($data, $video->mime)
                ->timeout(300)
                ->put($uploadUrl)
                ->throw();
        }

        fclose($handle);

        return $init->json('data.publish_id');
    }
}