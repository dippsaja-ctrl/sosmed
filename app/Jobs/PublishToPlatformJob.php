<?php

namespace App\Jobs;

use App\Models\PostDelivery;
use App\Models\SocialAccount;
use App\Services\SocialTokenService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class PublishToPlatformJob implements ShouldQueue
{
    use Batchable, Queueable;

    public int $tries   = 1;     // jangan retry otomatis: berisiko posting ganda
    public int $timeout = 900;

    public function __construct(public int $deliveryId) {}

    public function handle(SocialTokenService $tokens): void
    {
        $delivery = PostDelivery::with('post.media')->findOrFail($this->deliveryId);

        if ($delivery->status === 'success') {
            return;
        }

        $delivery->update(['status' => 'processing', 'error' => null]);

        try {
            $post = $delivery->post;
            $cfg  = config("social.platforms.{$delivery->platform}");

            $account = SocialAccount::where('user_id', $post->user_id)
                ->where('provider', $cfg['provider'])
                ->first();

            if (! $account) {
                throw new RuntimeException('Akun sosmed belum terhubung.');
            }

            // Refresh otomatis jika hampir kedaluwarsa (dari SocialTokenService sebelumnya)
            $token = $tokens->getValidToken($account);

            if (! $token) {
                throw new RuntimeException('Token tidak valid. Minta user menghubungkan ulang akun.');
            }

            $externalId = app($cfg['publisher'])->publish($post, $token, $account);

            $delivery->update([
                'status'       => 'success',
                'external_id'  => $externalId,
                'published_at' => now(),
            ]);
        } catch (Throwable $e) {
            report($e);

            // Tidak di-throw ulang supaya platform lain di batch tetap jalan
            $delivery->update([
                'status' => 'failed',
                'error'  => Str::limit($e->getMessage(), 1000),
            ]);
        }
    }
}