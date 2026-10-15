<?php

namespace App\Services\Platforms;

use App\Exceptions\PublishException;
use App\Models\Post;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Http;

/** Facebook Page lewat Graph API. Memakai Page access token dari long-lived user token. */
class FacebookPlatform extends Platform
{
    public function key(): string { return 'facebook'; }

    private function graph(): string
    {
        return "https://graph.facebook.com/{$this->version()}";
    }

    public function authUrl(string $state): string
    {
        return "https://www.facebook.com/{$this->version()}/dialog/oauth?".http_build_query([
            'client_id'    => $this->cfg('client_id'),
            'redirect_uri' => $this->redirectUri(),
            'state'        => $state,
            'scope'        => 'pages_show_list,pages_manage_posts,pages_read_engagement',
        ]);
    }

    public function exchangeCode(string $code): array
    {
        $user = $this->json(Http::get($this->graph().'/oauth/access_token', [
            'client_id' => $this->cfg('client_id'), 'client_secret' => $this->cfg('client_secret'),
            'redirect_uri' => $this->redirectUri(), 'code' => $code,
        ]), 'Tukar kode Facebook');

        $long = $this->json(Http::get($this->graph().'/oauth/access_token', [
            'grant_type' => 'fb_exchange_token', 'client_id' => $this->cfg('client_id'),
            'client_secret' => $this->cfg('client_secret'), 'fb_exchange_token' => $user['access_token'],
        ]), 'Token jangka panjang Facebook');

        $pages = $this->json(Http::get($this->graph().'/me/accounts', ['access_token' => $long['access_token']]), 'Daftar Page Facebook');
        $page = $pages['data'][0] ?? null;
        if (! $page) {
            throw new PublishException('Tidak ada Facebook Page yang bisa dikelola oleh akun ini.', false);
        }

        return [
            'handle'        => $page['name'].' (Page)',
            'external_id'   => $page['id'],
            'access_token'  => $page['access_token'],
            'refresh_token' => null,
            'expires_at'    => null, // Page token dari long-lived user token tidak kedaluwarsa
        ];
    }

    public function refresh(SocialAccount $account): void
    {
        // Page token tidak perlu di-refresh. Kalau token dicabut, pengguna harus Hubungkan ulang.
    }

    public function publish(Post $post, SocialAccount $account): array
    {
        $page = $account->external_id;
        $token = $account->access_token;
        $urls = $post->mediaUrls();
        $caption = (string) $post->caption;

        if ($post->type === 'photo') {
            $r = $this->json(Http::asForm()->timeout(120)->post($this->graph()."/$page/photos", [
                'url' => $urls[0], 'caption' => $caption, 'access_token' => $token,
            ]), 'Facebook foto');
            return ['url' => 'https://www.facebook.com/'.($r['post_id'] ?? $r['id']), 'note' => null];
        }

        if ($post->type === 'carousel') {
            $params = ['message' => $caption, 'access_token' => $token];
            foreach ($urls as $i => $u) {
                $p = $this->json(Http::asForm()->timeout(120)->post($this->graph()."/$page/photos", [
                    'url' => $u, 'published' => 'false', 'access_token' => $token,
                ]), 'Facebook unggah foto '.($i + 1));
                $params["attached_media[$i]"] = json_encode(['media_fbid' => $p['id']]);
            }
            $r = $this->json(Http::asForm()->timeout(120)->post($this->graph()."/$page/feed", $params), 'Facebook multi-foto');
            return ['url' => 'https://www.facebook.com/'.$r['id'], 'note' => null];
        }

        // video
        $r = $this->json(Http::asForm()->timeout(300)->post($this->graph()."/$page/videos", [
            'file_url' => $urls[0], 'description' => $caption, 'access_token' => $token,
        ]), 'Facebook video');
        return ['url' => 'https://www.facebook.com/watch/?v='.$r['id'], 'note' => 'Video mungkin masih diproses Facebook beberapa menit.'];
    }
}
