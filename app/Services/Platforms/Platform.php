<?php

namespace App\Services\Platforms;

use App\Exceptions\PublishException;
use App\Models\Post;
use App\Models\SocialAccount;
use Illuminate\Http\Client\Response;

/** Kontrak yang harus dipenuhi tiap platform. */
abstract class Platform
{
    abstract public function key(): string;

    /** URL halaman login/izin resmi platform (langkah 1 OAuth). */
    abstract public function authUrl(string $state): string;

    /**
     * Tukar "code" dari callback dengan token (langkah 2 OAuth).
     * @return array{handle:string,external_id:?string,access_token:string,refresh_token:?string,expires_at:mixed}
     */
    abstract public function exchangeCode(string $code): array;

    /** Perbarui access token (dipanggil scheduler / job sebelum kedaluwarsa). */
    abstract public function refresh(SocialAccount $account): void;

    /** @return array{url:?string,note:?string} */
    abstract public function publish(Post $post, SocialAccount $account): array;

    protected function cfg(string $key, mixed $default = null): mixed
    {
        return config("posting.platforms.{$this->key()}.$key", $default);
    }

    protected function redirectUri(): string
    {
        return route('oauth.callback', $this->key());
    }

    protected function version(): string
    {
        return config('posting.graph_version', 'v21.0');
    }

    /** Ubah response HTTP jadi array; lempar PublishException kalau gagal. */
    protected function json(Response $r, string $context): array
    {
        if ($r->failed()) {
            $j = $r->json();
            if (! is_array($j)) {
                $j = [];
            }
            $err = $j['error'] ?? null;
            if (is_array($err)) {
                $msg = $err['message'] ?? $err['error_user_msg'] ?? json_encode($err);
            } else {
                $msg = $err ?? ($j['error_description'] ?? $r->body());
            }
            throw new PublishException("$context (HTTP {$r->status()}): ".mb_substr((string) $msg, 0, 300), $r->serverError() || $r->status() === 429);
        }
        return $r->json() ?? [];
    }
}
