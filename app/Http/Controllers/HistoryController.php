<?php

namespace App\Http\Controllers;

use App\Jobs\PublishToPlatform;
use App\Models\Post;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('f', 'all');
        $q = Post::with('targets')->where('user_id', auth()->id())->whereIn('state', ['processing', 'done']);

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
        $target = $post->targets()->where('platform', $platform)->where('status', 'failed')->firstOrFail();

        $target->update(['status' => 'queued', 'error' => null, 'attempts' => 0]);
        $post->update(['state' => 'processing']);
        PublishToPlatform::dispatch($target, 1);

        return back()->with('ok', 'Mencoba lagi ke '.config("posting.platforms.$platform.label").'.');
    }

    public function retryAll(Post $post)
    {
        abort_unless($post->user_id === auth()->id(), 403);
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
