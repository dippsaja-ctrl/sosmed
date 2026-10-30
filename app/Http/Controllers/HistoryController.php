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
        $keyword = trim((string) $request->query('q', ''));

        $q = Post::with([
            'targets.histories' => fn ($h) => $h->latest(),
        ])
            ->where('user_id', auth()->id())
            ->whereIn('state', ['processing', 'done']);

        if ($filter === 'bad') {
            $q->whereHas(
                'targets',
                fn ($t) => $t->where('status', 'failed')
            );
        } elseif ($filter === 'ok') {
            $q->where('state', 'done')
                ->whereDoesntHave(
                    'targets',
                    fn ($t) => $t->where('status', '!=', 'success')
                );
        }

        // Cari caption atau nama platform jika minimal 2 karakter.
        if (mb_strlen($keyword) >= 2) {
            $q->where(function ($search) use ($keyword) {
                $search->where('caption', 'like', '%' . $keyword . '%')
                    ->orWhereHas('targets', function ($targets) use ($keyword) {
                        $targets->where(
                            'platform',
                            'like',
                            '%' . $keyword . '%'
                        );
                    });
            });
        }

        $posts = $q->latest()->limit(50)->get();

        // Permintaan pencarian langsung mengembalikan HTML hasil,
        // sehingga halaman tidak perlu dimuat ulang.
        if ($request->ajax()) {
            $html = '';

            foreach ($posts as $post) {
                $html .= view('partials.post-row', [
                    'post' => $post,
                    'mode' => 'history',
                ])->render();
            }

            return response()->json([
                'html' => $html,
                'count' => $posts->count(),
                'empty' => $posts->isEmpty(),
                'processing' => $posts->contains(
                    fn ($post) => $post->state === 'processing'
                ),
            ]);
        }

        return view('history.index', compact(
            'posts',
            'filter',
            'keyword'
        ));
    }

    public function retry(Post $post, string $platform)
    {
        abort_unless($post->user_id === auth()->id(), 403);

        if ($problem = $this->accountProblem($platform)) {
            return back()->withErrors(['retry' => $problem]);
        }

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

        return back()->with(
            'ok',
            'Mencoba lagi ke ' .
            config("posting.platforms.$platform.label", ucfirst($platform)) .
            '.'
        );
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
                'retry' => 'Perlu hubungkan ulang akun berikut: ' .
                    implode(', ', $labels),
            ]);
        }

        if ($started) {
            return $back->with(
                'ok',
                'Mencoba ulang platform: ' .
                    implode(', ', $started) . '.'
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

