<?php

namespace App\Http\Controllers;

use App\Jobs\PublishToPlatform;
use App\Models\Post;
use App\Models\SocialAccount;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('f', 'all');

<<<<<<< HEAD
        $q = Post::with(['targets.histories' => fn ($h) => $h->latest()])
            ->where('user_id', auth()->id())
            ->whereIn('state', ['processing', 'done']);
        $q = Post::with('targets')->where('user_id', auth()->id())->whereIn('state', ['processing', 'done']);
=======
        $q = Post::with([
            'targets.histories' => fn ($h) => $h->latest(),
        ])
            ->where('user_id', auth()->id())
            ->whereIn('state', ['processing', 'done']);
>>>>>>> 645607949a780e1c0ec292546c23efdc46250fff

        if ($filter === 'bad') {
            $q->whereHas('targets', fn ($t) => $t->where('status', 'failed'));
        } elseif ($filter === 'ok') {
            $q->where('state', 'done')
                ->whereDoesntHave(
                    'targets',
                    fn ($t) => $t->where('status', '!=', 'success')
                );
        }

        $posts = $q->latest()->limit(50)->get();

        return view('history.index', compact('posts', 'filter'));
    }

    public function retry(Post $post, string $platform)
    {
        abort_unless($post->user_id === auth()->id(), 403);

        if ($problem = $this->accountProblem($platform)) {
            return back()->withErrors(['retry' => $problem]);
        }

        // Pastikan hanya target berstatus gagal yang bisa dicoba ulang.
        $claimed = $post->targets()
            ->where('platform', $platform)
            ->where('status', 'failed')
            ->update([
                'status' => 'queued',
                'error' => null,
                'attempts' => 0,
            ]);

        if (! $claimed) {
            return back()->withErrors([
                'retry' => 'Target ini tidak dalam status gagal atau sudah dicoba ulang.',
            ]);
        }

        $target = $post->targets()
            ->where('platform', $platform)
            ->firstOrFail();

        $post->update(['state' => 'processing']);

        PublishToPlatform::dispatch($target, 1)->onQueue('uploads');
<<<<<<< HEAD

        $target = $post->targets()->where('platform', $platform)->where('status', 'failed')->firstOrFail();

        $target->update(['status' => 'queued', 'error' => null, 'attempts' => 0]);
        $post->update(['state' => 'processing']);
        PublishToPlatform::dispatch($target, 1);


        return back()->with('ok', 'Mencoba lagi ke '.config("posting.platforms.$platform.label").'.');
=======

        return back()->with(
            'ok',
            'Mencoba lagi ke '.config("posting.platforms.$platform.label", ucfirst($platform)).'.'
        );
>>>>>>> 645607949a780e1c0ec292546c23efdc46250fff
    }

    public function retryAll(Post $post)
    {
        abort_unless($post->user_id === auth()->id(), 403);

        $platforms = $post->targets()
            ->where('status', 'failed')
            ->pluck('platform');

        abort_if($platforms->isEmpty(), 404);

        $started = [];
        $skipped = [];

        foreach ($platforms as $platform) {
            if ($this->accountProblem($platform)) {
                $skipped[] = $platform;
                continue;
            }

            // Klaim target yang masih gagal agar tidak diproses dua kali.
            $claimed = $post->targets()
                ->where('platform', $platform)
                ->where('status', 'failed')
                ->update([
                    'status' => 'queued',
                    'error' => null,
                    'attempts' => 0,
                ]);

            if (! $claimed) {
                continue;
            }

            $target = $post->targets()
                ->where('platform', $platform)
                ->first();

            if (! $target) {
                continue;
            }

            PublishToPlatform::dispatch($target, 1)->onQueue('uploads');

            $started[] = $platform;
        }

        if ($started) {
            $post->update(['state' => 'processing']);
        }

        $back = back();

        if ($skipped) {
            $labels = array_map(
                fn ($platform) => config(
                    "posting.platforms.$platform.label",
                    ucfirst($platform)
                ),
                $skipped
            );

            $back->withErrors([
                'retry' => 'Perlu hubungkan ulang akun berikut: '.implode(', ', $labels),
            ]);
        }

        if ($started) {
            return $back->with(
                'ok',
                'Mencoba ulang platform: '.implode(', ', $started).'.'
            );
        }

        return $back;
    }

    /**
     * Memeriksa apakah akun media sosial siap digunakan.
     */
    private function accountProblem(string $platform): ?string
    {
        $account = SocialAccount::where('user_id', auth()->id())
            ->where('platform', $platform)
            ->first();

        $label = config(
            "posting.platforms.$platform.label",
            ucfirst($platform)
        );

        if (! $account) {
            return "Akun $label belum terhubung.";
        }

        if ($account->needs_reauth) {
            return "Akun $label perlu dihubungkan ulang sebelum mencoba lagi.";
        }

        return null;
    }
}
<<<<<<< HEAD
        $failed = $post->targets()->where('status', 'failed')->get();
        abort_if($failed->isEmpty(), 404);

        $post->update(['state' => 'processing']);
        foreach ($failed as $target) {
            $target->update(['status' => 'queued', 'error' => null, 'attempts' => 0]);
            PublishToPlatform::dispatch($target, 1);
        }
        return back()->with('ok', 'Mencoba lagi semua platform yang gagal.');
    }
}
=======
>>>>>>> 645607949a780e1c0ec292546c23efdc46250fff
