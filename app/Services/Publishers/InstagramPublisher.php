<?php

namespace App\Services\Publishers;

use App\Models\Post;
use App\Models\PostMedia;
use App\Models\SocialAccount;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class InstagramPublisher implements PlatformPublisher
{
    public function publish(Post $post, string $token, SocialAccount $account): string
    {
        $igId = $account->meta['ig_user_id']
            ?? throw new RuntimeException('ig_user_id belum tersimpan di social_accounts.meta');

        $media = $post->media->values();

        if ($post->type === 'carousel') {
            // 1. Buat container tiap slide SECARA PARALEL
            $responses = Http::pool(fn (Pool $pool) => $media->map(
                fn (PostMedia $m, int $i) => $pool->as((string) $i)->asForm()->post(
                    $this->url("{$igId}/media"),
                    $this->itemPayload($m, $token, carousel: true)
                )
            )->all());

            $childIds = [];
            foreach ($media as $i => $m) {
                $r = $responses[(string) $i];

                if (! $r instanceof Response || ! $r->successful()) {
                    throw new RuntimeException("Slide #{$i} gagal dibuat: " . ($r instanceof Response ? $r->body() : $r->getMessage()));
                }

                $childIds[] = $r->json('id');
                if ($m->isVideo()) {
                    $this->waitUntilReady($r->json('id'), $token);
                }
            }

            // 2. Container carousel
            $containerId = Http::asForm()->post($this->url("{$igId}/media"), [
                'media_type'   => 'CAROUSEL',
                'children'     => implode(',', $childIds),
                'caption'      => $post->caption ?? '',
                'access_token' => $token,
            ])->throw()->json('id');
        } else {
            $payload = $this->itemPayload($media->first(), $token, carousel: false);
            $payload['caption'] = $post->caption ?? '';

            $containerId = Http::asForm()->post($this->url("{$igId}/media"), $payload)->throw()->json('id');

            if ($media->first()->isVideo()) {
                $this->waitUntilReady($containerId, $token);
            }
        }

        // 3. Publish
        return Http::asForm()->post($this->url("{$igId}/media_publish"), [
            'creation_id'  => $containerId,
            'access_token' => $token,
        ])->throw()->json('id');
    }

    private function itemPayload(PostMedia $m, string $token, bool $carousel): array
    {
        $payload = ['access_token' => $token];

        if ($m->isVideo()) {
            $payload['media_type'] = $carousel ? 'VIDEO' : 'REELS';
            $payload['video_url']  = $m->publicUrl();
        } else {
            $payload['image_url'] = $m->publicUrl();
        }

        if ($carousel) {
            $payload['is_carousel_item'] = 'true';
        }

        return $payload;
    }

    private function waitUntilReady(string $containerId, string $token): void
    {
        for ($i = 0; $i < 30; $i++) {
            $status = Http::get($this->url($containerId), [
                'fields'       => 'status_code',
                'access_token' => $token,
            ])->throw()->json('status_code');

            if ($status === 'FINISHED') {
                return;
            }
            if (in_array($status, ['ERROR', 'EXPIRED'], true)) {
                throw new RuntimeException("Container {$containerId} berstatus {$status}");
            }

            sleep(5);
        }

        throw new RuntimeException("Container {$containerId} tidak selesai diproses (timeout)");
    }

    private function url(string $path): string
    {
        return 'https://graph.facebook.com/' . config('social.graph_version') . '/' . $path;
    }
}