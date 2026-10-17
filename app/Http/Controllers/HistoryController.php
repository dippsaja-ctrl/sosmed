<?php

namespace App\Http\Controllers;

use App\Jobs\PublishToPlatform;
use App\Models\Post;
<<<<<<< HEAD
use App\Models\SocialAccount;
=======
>>>>>>> bebel
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
=======
        $q = Post::with('targets')->where('user_id', auth()->id())->whereIn('state', ['processing', 'done']);
>>>>>>> bebel

        if ($filter === 'bad') {
            $q->whereHas('targets', fn ($t) => $t->where('status', 'failed'));
        } elseif ($filter === 'ok') {
            $q->where('state', 'done')->whereDoesntHave('targets', fn ($t) => $t->where('status', '!=', 'success'));
        }

        $posts = $q->latest()->limit(50)->get();
        return view('history.index', compact('posts', 'filter'));
    }

    public function retry(Post $post, string $platform)
    {
        abort_unless($post->user_id === auth()->id(), 403);
<<<<<<< HEAD

        if ($problem = $this->accountProblem($platform)) {
            return back()->withErrors(['retry' => $problem]);
        }

        // Klaim atomik: hanya satu request yang bisa mengubah failed -> queued
        $claimed = $post->targets()
            ->where('platform', $platform)
            ->where('status', 'failed')
            ->update(['status' => 'queued', 'error' => null, 'attempts' => 0]);

        if (! $claimed) {
            return back()->withErrors(['retry' => 'Target ini tidak dalam status gagal (mungkin sudah diulang).']);
        }

        $target = $post->targets()->where('platform', $platform)->firstOrFail();

        $post->update(['state' => 'processing']);
        PublishToPlatform::dispatch($target, 1)->onQueue('uploads');
=======
        $target = $post->targets()->where('platform', $platform)->where('status', 'failed')->firstOrFail();

        $target->update(['status' => 'queued', 'error' => null, 'attempts' => 0]);
        $post->update(['state' => 'processing']);
        PublishToPlatform::dispatch($target, 1);
>>>>>>> bebel

        return back()->with('ok', 'Mencoba lagi ke '.config("posting.platforms.$platform.label").'.');
    }

    public function retryAll(Post $post)
    {
        abort_unless($post->user_id === auth()->id(), 403);
<<<<<<< HEAD

        $platforms = $post->targets()->where('status', 'failed')->pluck('platform');
        abort_if($platforms->isEmpty(), 404);

        $started = [];
        $skipped = [];

        foreach ($platforms as $platform) {
            if ($this->accountProblem($platform)) {
                $skipped[] = $platform;
                continue;
            }

            $claimed = $post->targets()
                ->where('platform', $platform)
                ->where('status', 'failed')
                ->update(['status' => 'queued', 'error' => null, 'attempts' => 0]);

            if (! $claimed) {
                continue;
            }

            $target = $post->targets()->where('platform', $platform)->first();
            PublishToPlatform::dispatch($target, 1)->onQueue('uploads');
            $started[] = $platform;
        }

        if ($started) {
            $post->update(['state' => 'processing']);
        }

        $back = back();
        if ($skipped) {
            $back->withErrors(['retry' => 'Perlu hubungkan ulang akun: '.implode(', ', $skipped)]);
        }

        return $started
            ? $back->with('ok', 'Mencoba lagi: '.implode(', ', $started).'.')
            : $back;
    }

    /** Mengembalikan pesan masalah, atau null jika akun siap dipakai. */
    private function accountProblem(string $platform): ?string
    {
        $account = SocialAccount::where('user_id', auth()->id())
            ->where('platform', $platform)
            ->first();

        $label = config("posting.platforms.$platform.label", ucfirst($platform));

        if (! $account) {
            return "Akun $label belum terhubung.";
        }
        if ($account->needs_reauth) {
            return "Akun $label perlu dihubungkan ulang sebelum mencoba lagi.";
        }

        return null;
    }
}
=======
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
>>>>>>> bebel
