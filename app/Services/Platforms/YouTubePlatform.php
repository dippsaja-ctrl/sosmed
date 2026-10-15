<?php

namespace App\Services\Platforms;

use App\Exceptions\PublishException;
use App\Models\Post;
use App\Models\SocialAccount;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Facades\Http;

/**
 * YouTube Data API v3, upload resumable.
 * Proyek Google yang belum diaudit memaksa video menjadi privat.
 */
class YouTubePlatform extends Platform
{
    public function key(): string { return 'youtube'; }

    public function authUrl(string $state): string
    {
        return 'https://accounts.google.com/o/oauth2/v2/auth?'.http_build_query([
            'client_id'     => $this->cfg('client_id'),
            'redirect_uri'  => $this->redirectUri(),
            'response_type' => 'code',
            'scope'         => 'https://www.googleapis.com/auth/youtube.upload https://www.googleapis.com/auth/youtube.readonly',
            'access_type'   => 'offline',   // supaya dapat refresh_token
            'prompt'        => 'consent',
            'state'         => $state,
        ]);
    }

    public function exchangeCode(string $code): array
    {
        $t = $this->json(Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $this->cfg('client_id'), 'client_secret' => $this->cfg('client_secret'),
            'code' => $code, 'grant_type' => 'authorization_code', 'redirect_uri' => $this->redirectUri(),
        ]), 'Tukar kode Google');

        $ch = Http::withToken($t['access_token'])->get('https://www.googleapis.com/youtube/v3/channels', ['part' => 'snippet', 'mine' => 'true'])->json();

        return [
            'handle'        => data_get($ch, 'items.0.snippet.title', 'Channel YouTube'),
            'external_id'   => data_get($ch, 'items.0.id'),
            'access_token'  => $t['access_token'],
            'refresh_token' => $t['refresh_token'] ?? null,
            'expires_at'    => now()->addSeconds($t['expires_in'] ?? 3600),
        ];
    }

    public function refresh(SocialAccount $account): void
    {
        if (! $account->refresh_token) {
            throw new PublishException('Refresh token Google tidak ada. Hubungkan ulang akun.', false);
        }
        $t = $this->json(Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $this->cfg('client_id'), 'client_secret' => $this->cfg('client_secret'),
            'refresh_token' => $account->refresh_token, 'grant_type' => 'refresh_token',
        ]), 'Refresh token Google');
        $account->update([
            'access_token' => $t['access_token'],
            'expires_at'   => now()->addSeconds($t['expires_in'] ?? 3600),
        ]);
    }

    public function publish(Post $post, SocialAccount $account): array
    {
        $yt = $post->yt_meta ?? [];
        $path = $post->mediaPaths()[0];
        $size = filesize($path);
        $mime = mime_content_type($path) ?: 'video/mp4';

        $meta = [
            'snippet' => [
                'title'       => mb_substr($yt['title'] ?? 'Tanpa judul', 0, 100),
                'description' => ($yt['desc'] ?? '') !== '' ? $yt['desc'] : (string) $post->caption,
                'tags'        => array_values(array_filter(array_map('trim', explode(',', $yt['tags'] ?? '')))),
                'categoryId'  => $yt['category'] ?? '22',
            ],
            'status' => [
                'privacyStatus'           => $yt['visibility'] ?? 'private',
                'selfDeclaredMadeForKids' => false,
            ],
        ];

        // 1) Mulai sesi upload resumable
        $init = Http::withToken($account->access_token)->withHeaders([
            'X-Upload-Content-Length' => $size, 'X-Upload-Content-Type' => $mime,
        ])->post('https://www.googleapis.com/upload/youtube/v3/videos?uploadType=resumable&part=snippet,status', $meta);
        $this->json($init, 'YouTube mulai upload');
        $uploadUrl = $init->header('Location');
        if (! $uploadUrl) {
            throw new PublishException('YouTube tidak mengembalikan URL upload.', true);
        }

        // 2) Kirim isi video (di-stream, tidak dimuat penuh ke memori)
        $fh = fopen($path, 'rb');
        try {
            $r = Http::timeout(0)->withBody(Utils::streamFor($fh), $mime)->put($uploadUrl);
        } finally {
            if (is_resource($fh)) fclose($fh);
        }
        $j = $this->json($r, 'YouTube upload');

        $note = null;
        if (($yt['visibility'] ?? 'private') === 'public') {
            $note = 'Jika proyek Google belum diaudit, video otomatis dikunci privat.';
        }
        return ['url' => 'https://youtu.be/'.$j['id'], 'note' => $note];
    }
}
