<?php

namespace App\Services\Platforms;

use App\Exceptions\PublishException;
use App\Models\Post;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Http;

/**
 * TikTok Content Posting API (Direct Post).
 * Sebelum aplikasi lolos audit TikTok, semua postingan hanya bisa privat (SELF_ONLY).
 */
class TikTokPlatform extends Platform
{
    private const API = 'https://open.tiktokapis.com/v2';

    public function key(): string
    {
        return 'tiktok';
    }

    public function authUrl(string $state): string
    {
        return 'https://www.tiktok.com/v2/auth/authorize/?'.http_build_query([
            'client_key' => $this->cfg('client_id'),
            'scope' => 'user.info.basic,video.upload',
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri(),
            'state' => $state,
        ]);
    }

    public function exchangeCode(string $code): array
    {
        $t = $this->tokenCall([
            'client_key' => $this->cfg('client_id'), 'client_secret' => $this->cfg('client_secret'),
            'code' => $code, 'grant_type' => 'authorization_code', 'redirect_uri' => $this->redirectUri(),
        ]);

        $info = Http::withToken($t['access_token'])->get(self::API.'/user/info/', ['fields' => 'open_id,display_name'])->json();

        return [
            'handle' => data_get($info, 'data.user.display_name', 'Akun TikTok'),
            'external_id' => $t['open_id'] ?? null,
            'access_token' => $t['access_token'],
            'refresh_token' => $t['refresh_token'] ?? null,
            'expires_at' => now()->addSeconds($t['expires_in'] ?? 86400),
        ];
    }

    public function refresh(SocialAccount $account): void
    {
        if (! $account->refresh_token) {
            throw new PublishException('Refresh token TikTok tidak ada. Hubungkan ulang akun.', false);
        }
        $t = $this->tokenCall([
            'client_key' => $this->cfg('client_id'), 'client_secret' => $this->cfg('client_secret'),
            'grant_type' => 'refresh_token', 'refresh_token' => $account->refresh_token,
        ]);
        $account->update([
            'access_token' => $t['access_token'],
            'refresh_token' => $t['refresh_token'] ?? $account->refresh_token,
            'expires_at' => now()->addSeconds($t['expires_in'] ?? 86400),
        ]);
    }

    private function tokenCall(array $form): array
    {
        $j = $this->json(Http::asForm()->post(self::API.'/oauth/token/', $form), 'Token TikTok');
        if (isset($j['error'])) {
            throw new PublishException('Token TikTok: '.($j['error_description'] ?? $j['error']), false);
        }

        return $j;
    }

    public function publish(Post $post, SocialAccount $account): array
    {
        $privacy = $this->cfg('privacy', 'SELF_ONLY');
        $title = mb_substr((string) $post->caption, 0, 2200);

        if ($post->type === 'carousel') {
            // Foto diambil TikTok dari URL; domain/prefix URL harus diverifikasi di dashboard developer.
            $r = $this->call('/post/publish/content/init/', [
                'post_info' => ['title' => mb_substr($title, 0, 90), 'description' => $title, 'privacy_level' => $privacy],
                'source_info' => ['source' => 'PULL_FROM_URL', 'photo_cover_index' => 0, 'photo_images' => $post->mediaUrls()],
                'post_mode' => 'DIRECT_POST', 'media_type' => 'PHOTO',
            ], $account);
            $publishId = $r['data']['publish_id'];
        } else {
            $publishId = $this->uploadVideo($post->mediaPaths()[0], $title, $privacy, $account);
        }

        $status = $this->waitForStatus($publishId, $account);

        return [
            'url' => null, // TikTok baru memberi ID postingan setelah lolos moderasi
            'note' => ($privacy === 'SELF_ONLY' ? 'Terbit sebagai privat (menunggu audit TikTok). ' : '').'publish_id: '.$publishId.' ('.$status.')',
        ];
    }

    private function uploadVideo(string $path, string $title, string $privacy, SocialAccount $account): string
    {
        $size = filesize($path);
        $chunk = 10 * 1024 * 1024;
        if ($size < $chunk) {
            $chunk = $size;
            $count = 1;
        } else {
            $count = (int) ceil($size / $chunk);
        }

        $init = $this->call('/post/publish/video/init/', [
            'post_info' => ['title' => $title, 'privacy_level' => $privacy],
            'source_info' => ['source' => 'FILE_UPLOAD', 'video_size' => $size, 'chunk_size' => $chunk, 'total_chunk_count' => $count],
        ], $account);

        $uploadUrl = $init['data']['upload_url'];
        $fh = fopen($path, 'rb');
        try {
            for ($i = 0; $i < $count; $i++) {
                $start = $i * $chunk;
                $len = ($i === $count - 1) ? $size - $start : $chunk; // chunk terakhir menampung sisa
                fseek($fh, $start);
                $data = fread($fh, $len);
                $r = Http::timeout(300)->withHeaders([
                    'Content-Range' => 'bytes '.$start.'-'.($start + $len - 1).'/'.$size,
                ])->withBody($data, 'video/mp4')->put($uploadUrl);
                if ($r->failed()) {
                    throw new PublishException('Upload chunk TikTok gagal (HTTP '.$r->status().').', $r->serverError());
                }
            }
        } finally {
            fclose($fh);
        }

        return $init['data']['publish_id'];
    }

    private function waitForStatus(string $publishId, SocialAccount $account): string
    {
        for ($i = 0; $i < 24; $i++) {
            $r = $this->call('/post/publish/status/fetch/', ['publish_id' => $publishId], $account);
            $s = $r['data']['status'] ?? '';
            if ($s === 'PUBLISH_COMPLETE') {
                return $s;
            }
            if ($s === 'FAILED') {
                throw new PublishException('TikTok gagal: '.($r['data']['fail_reason'] ?? 'tidak diketahui'), false);
            }
            sleep(5);
        }

        return 'PROCESSING'; // masih diproses; dianggap terkirim
    }

    private function call(string $path, array $body, SocialAccount $account): array
    {
        $r = Http::withToken($account->access_token)->timeout(120)->post(self::API.$path, $body);
        $j = $this->json($r, 'TikTok API');
        $code = data_get($j, 'error.code', 'ok');
        if ($code !== 'ok') {
            throw new PublishException("TikTok API: $code — ".data_get($j, 'error.message'), in_array($code, ['rate_limit_exceeded', 'internal_error']));
        }

        return $j;
    }
}
