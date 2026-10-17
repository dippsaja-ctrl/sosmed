<?php

namespace App\Services\Platforms;

use App\Exceptions\PublishException;
use App\Models\Post;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Http;

/**
 * Instagram API with Instagram Login (scope instagram_business_content_publish).
 * Catatan: Instagram mengambil media lewat URL, jadi URL file harus bisa diakses PUBLIK.
 */
class InstagramPlatform extends Platform
{
    public function key(): string { return 'instagram'; }

    public function authUrl(string $state): string
    {
        return 'https://www.instagram.com/oauth/authorize?'.http_build_query([
            'client_id'     => $this->cfg('client_id'),
            'redirect_uri'  => $this->redirectUri(),
            'response_type' => 'code',
            'scope'         => 'instagram_business_basic,instagram_business_content_publish',
            'state'         => $state,
        ]);
    }

    public function exchangeCode(string $code): array
    {
        $r = $this->json(Http::asForm()->post('https://api.instagram.com/oauth/access_token', [
            'client_id'     => $this->cfg('client_id'),
            'client_secret' => $this->cfg('client_secret'),
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->redirectUri(),
            'code'          => $code,
        ]), 'Tukar kode Instagram');
        $short = $r['data'][0] ?? $r;

        $long = $this->json(Http::get('https://graph.instagram.com/access_token', [
            'grant_type'    => 'ig_exchange_token',
            'client_secret' => $this->cfg('client_secret'),
            'access_token'  => $short['access_token'],
        ]), 'Token jangka panjang Instagram');

        $me = $this->json(Http::get("https://graph.instagram.com/{$this->version()}/me", [
            'fields' => 'user_id,username', 'access_token' => $long['access_token'],
        ]), 'Profil Instagram');

        return [
            'handle'        => '@'.($me['username'] ?? 'instagram'),
            'external_id'   => (string) ($me['user_id'] ?? $short['user_id'] ?? ''),
            'access_token'  => $long['access_token'],
            'refresh_token' => null,
            'expires_at'    => now()->addSeconds($long['expires_in'] ?? 60 * 86400),
        ];
    }

    public function refresh(SocialAccount $account): void
    {
        $r = $this->json(Http::get('https://graph.instagram.com/refresh_access_token', [
            'grant_type' => 'ig_refresh_token', 'access_token' => $account->access_token,
        ]), 'Refresh token Instagram');
        $account->update([
            'access_token' => $r['access_token'],
            'expires_at'   => now()->addSeconds($r['expires_in'] ?? 60 * 86400),
        ]);
    }

    public function publish(Post $post, SocialAccount $account): array
    {
        $id = $account->external_id;
        $urls = $post->mediaUrls();
        $caption = (string) $post->caption;

        if ($post->type === 'photo') {
            $container = $this->call("/$id/media", ['image_url' => $urls[0], 'caption' => $caption], $account);
        } elseif ($post->type === 'video') {
            $container = $this->call("/$id/media", [
                'media_type' => 'REELS', 'video_url' => $urls[0], 'caption' => $caption, 'share_to_feed' => 'true',
            ], $account);
            $this->waitUntilReady($container['id'], $account);
        } else { // carousel
            $children = [];
            foreach ($urls as $u) {
                $children[] = $this->call("/$id/media", ['image_url' => $u, 'is_carousel_item' => 'true'], $account)['id'];
            }
            $container = $this->call("/$id/media", [
                'media_type' => 'CAROUSEL', 'children' => implode(',', $children), 'caption' => $caption,
            ], $account);
        }

        $media = $this->call("/$id/media_publish", ['creation_id' => $container['id']], $account);

        $link = Http::get($this->base()."/{$media['id']}", ['fields' => 'permalink', 'access_token' => $account->access_token])->json('permalink');
        return ['url' => $link, 'note' => null];
    }

    private function base(): string
    {
        return "https://graph.instagram.com/{$this->version()}";
    }

    private function call(string $path, array $params, SocialAccount $account): array
    {
        $r = Http::asForm()->timeout(120)->post($this->base().$path, $params + ['access_token' => $account->access_token]);
        return $this->json($r, 'Instagram API');
    }

    /** Video diproses Instagram secara asinkron; tunggu sampai FINISHED. */
    private function waitUntilReady(string $containerId, SocialAccount $account): void
    {
        for ($i = 0; $i < 36; $i++) {
            $s = Http::get($this->base()."/$containerId", ['fields' => 'status_code,status', 'access_token' => $account->access_token])->json();
            $code = $s['status_code'] ?? '';
            if ($code === 'FINISHED') return;
            if ($code === 'ERROR' || $code === 'EXPIRED') {
                throw new PublishException('Instagram gagal memproses video: '.($s['status'] ?? $code), false);
            }
            sleep(5);
        }
        throw new PublishException('Instagram belum selesai memproses video (timeout).', true);
    }
}
