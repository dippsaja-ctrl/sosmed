<?php

namespace App\Jobs;

use App\Exceptions\PublishException;
use App\Models\Post;
use App\Models\PostTarget;
use App\Models\SocialAccount;
use App\Notifications\AppNotice;
use App\Services\Platforms\PlatformManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

/**
 * Satu job = satu platform untuk satu posting.
 * Posting langsung: tries=1. Posting terjadwal: tries=3 dengan jeda 5 dan 15 menit.
 */
class PublishToPlatform implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 1800;

    public function __construct(public PostTarget $target, int $maxTries = 3)
    {
        $this->tries = $maxTries;
    }

    public function backoff(): array
    {
        return [300, 900];
    }

    public function handle(PlatformManager $platforms): void
    {
        $target = $this->target->fresh(['post']);
        if (! $target || $target->status === 'success' || $target->post->state === 'canceled') {
            return;
        }

        $target->update([
            'status'   => $this->attempts() > 1 ? 'retrying' : 'sending',
            'attempts' => $this->attempts(),
            'error'    => null,
        ]);

        try {
            $account = SocialAccount::where('user_id', $target->post->user_id)
                ->where('platform', $target->platform)->first();
            if (! $account) {
                throw new PublishException('Akun belum terhubung atau sudah diputus.', false);
            }
            if ($account->isExpired() && ! $account->refresh_token && $account->platform !== 'instagram') {
                throw new PublishException('Access token kedaluwarsa. Hubungkan ulang akun.', false);
            }

            $client = $platforms->for($target->platform);
            if ($account->expiresSoon() || $account->isExpired()) {
                $client->refresh($account);
                $account->refresh(); // muat ulang model dari database
            }

            $result = $client->publish($target->post, $account);

            $target->update([
                'status'       => 'success',
                'external_url' => $result['url'] ?? null,
                'note'         => $result['note'] ?? null,
                'published_at' => now(),
                'error'        => null,
            ]);
            self::finalize($target->post);
        } catch (Throwable $e) {
            $target->update(['error' => mb_substr($e->getMessage(), 0, 1000)]);

            if ($e instanceof PublishException && ! $e->retryable) {
                $this->fail($e);           // kesalahan permanen: jangan diulang
                return;
            }
            if ($this->attempts() < $this->tries) {
                $target->update(['status' => 'retrying']);
            }
            throw $e;                      // Laravel menjadwalkan ulang sesuai backoff()
        }
    }

    /** Dipanggil Laravel setelah semua percobaan habis. */
    public function failed(Throwable $e): void
    {
        $target = PostTarget::with('post')->find($this->target->id);
        if (! $target) return;

        $target->update(['status' => 'failed', 'error' => mb_substr($e->getMessage(), 0, 1000)]);
        self::finalize($target->post);
    }

    /** Kalau semua platform selesai (berhasil/gagal), tandai posting selesai dan kirim notifikasi. */
    public static function finalize(Post $post): void
    {
        if ($post->targets()->whereIn('status', ['queued', 'sending', 'retrying'])->exists()) {
            return;
        }
        // update atomik: hanya satu job yang "menang" dan mengirim notifikasi
        if (Post::whereKey($post->id)->where('state', '!=', 'done')->update(['state' => 'done']) === 0) {
            return;
        }

        $targets = $post->targets()->get();
        $bad = $targets->where('status', 'failed')->pluck('platform')->map(fn ($p) => config("posting.platforms.$p.label"));
        $name = '"'.mb_substr($post->title(), 0, 32).'"';

        $post->user->notify(new AppNotice(
            $bad->isEmpty() ? 'ok' : 'bad',
            $bad->isEmpty() ? "Posting $name terbit di semua platform." : "Posting $name gagal di ".$bad->implode(', ').'. Buka Riwayat untuk mencoba lagi.'
        ));
    }
}
