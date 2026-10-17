<?php

namespace App\Http\Controllers;

use App\Jobs\PublishToPlatform;
use App\Models\Post;
use App\Models\SocialAccount;
<<<<<<< HEAD
use App\Models\ScheduledPost;
=======
>>>>>>> bebel
use App\Support\PostValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    private function own(Post $post): void
    {
        abort_unless($post->user_id === auth()->id(), 403);
    }

    private function accounts()
    {
        return SocialAccount::where('user_id', auth()->id())->get()->keyBy('platform');
    }

    public function create()
    {
        return view('posts.create', [
            'platforms' => config('posting.platforms'),
            'accounts'  => $this->accounts(),
        ]);
    }

    /** Simpan sebagai DRAF lalu tampilkan pratinjau. Belum ada yang dikirim ke platform. */
    public function store(Request $request)
    {
        $keys = implode(',', array_keys(config('posting.platforms')));
        $data = $request->validate([
            'type'            => ['required', 'in:video,carousel,photo'],
            'targets'         => ['required', 'array', 'min:1'],
            'targets.*'       => ["in:$keys"],
            'caption'         => ['nullable', 'string', 'max:63000'],
            'media'           => ['required', 'array', 'min:1', 'max:35'],
            'media.*'         => ['file', 'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/quicktime', 'max:2097152'],
            'video_duration'  => ['nullable', 'numeric', 'min:0'],
            'mode'            => ['required', 'in:now,schedule'],
            'scheduled_at'    => ['required_if:mode,schedule', 'nullable', 'date', 'after:+1 minute'],
            'yt.title'        => ['nullable', 'string', 'max:100'],
            'yt.desc'         => ['nullable', 'string', 'max:5000'],
            'yt.tags'         => ['nullable', 'string', 'max:500'],
            'yt.category'     => ['nullable', 'in:22,24,27,28,25'],
            'yt.visibility'   => ['nullable', 'in:private,unlisted,public'],
        ], [
            'scheduled_at.after' => 'Jadwal minimal 1 menit dari sekarang.',
            'media.*.mimetypes'  => 'Format file harus JPG, PNG, WEBP, MP4, atau MOV.',
        ]);

        // Semua platform tujuan harus sudah terhubung.
        $connected = $this->accounts()->keys()->all();
        $missing = array_diff($data['targets'], $connected);
        if ($missing) {
            return back()->withInput()->withErrors(['targets' => 'Akun belum terhubung: '.implode(', ', $missing)]);
        }

        // Kumpulkan metadata media untuk validasi per platform.
        $files = $request->file('media');
        $media = [];
        foreach ($files as $f) {
            $isVideo = str_starts_with((string) $f->getMimeType(), 'video/');
            $dim = $isVideo ? false : @getimagesize($f->getRealPath());
            $media[] = [
                'name'     => $f->getClientOriginalName(),
                'kind'     => $isVideo ? 'video' : 'image',
                'mime'     => $f->getMimeType(),
                'size'     => $f->getSize(),
                'width'    => $dim ? $dim[0] : null,
                'height'   => $dim ? $dim[1] : null,
                'duration' => $isVideo && isset($data['video_duration']) ? (int) round($data['video_duration']) : null,
            ];
        }

        $yt = $request->input('yt', []);
        $errors = PostValidator::check($data['type'], $data['targets'], $media, (string) ($data['caption'] ?? ''), $yt);
        if ($errors) {
            return back()->withInput()->with('platform_errors', $errors);
        }

        // Validasi lolos: simpan file lalu buat draf.
        $dir = 'posts/'.Str::uuid();
        foreach ($files as $i => $f) {
            $media[$i]['path'] = $f->store($dir, 'public');
        }

        $post = DB::transaction(function () use ($data, $media, $yt) {
            $post = Post::create([
                'user_id'      => auth()->id(),
                'type'         => $data['type'],
                'caption'      => $data['caption'] ?? '',
                'yt_meta'      => $yt,
                'media'        => $media,
                'mode'         => $data['mode'],
                'scheduled_at' => $data['mode'] === 'schedule' ? Carbon::parse($data['scheduled_at']) : null,
                'state'        => 'draft',
            ]);
            foreach ($data['targets'] as $pk) {
                $post->targets()->create(['platform' => $pk, 'status' => 'queued']);
            }
            return $post;
        });

        return redirect()->route('posts.preview', $post);
    }

    public function preview(Post $post)
    {
        $this->own($post);
        abort_unless($post->state === 'draft', 404);
        $post->load('targets');
        return view('posts.preview', ['post' => $post, 'platforms' => config('posting.platforms'), 'accounts' => $this->accounts()]);
    }

    public function confirm(Post $post)
    {
        $this->own($post);
        abort_unless($post->state === 'draft', 404);

        if ($post->mode === 'schedule') {
            if ($post->scheduled_at->lt(now()->addMinute())) {
                return redirect()->route('posts.preview', $post)->withErrors(['jadwal' => 'Waktu jadwal sudah lewat. Buat ulang posting dengan jadwal baru.']);
            }
            $post->update(['state' => 'scheduled']);
            return redirect()->route('schedule.index')->with('ok', 'Posting dijadwalkan dan akan terbit otomatis.');
        }

        $post->update(['state' => 'processing']);
        foreach ($post->targets as $target) {
<<<<<<< HEAD
            PublishToPlatform::dispatch($target, 1)->onQueue('uploads');
=======
            PublishToPlatform::dispatch($target, 1);
>>>>>>> bebel
        }
        return redirect()->route('history.index')->with('ok', 'Posting dikirim ke antrean. Status diperbarui otomatis di Riwayat.');
    }

    public function discard(Post $post)
    {
        $this->own($post);
        abort_unless($post->state === 'draft', 404);
        $this->deleteFiles($post);
        $post->delete();
        return redirect()->route('posts.create')->with('ok', 'Draf dibuang.');
    }

    /* ---- Edit & batalkan jadwal (selama belum terbit) ---- */

    public function edit(Post $post)
    {
        $this->own($post);
        abort_unless($post->state === 'scheduled', 404);
        $post->load('targets');
        $accounts = $this->accounts();
        $platforms = collect(config('posting.platforms'))
            ->filter(fn ($p, $k) => isset($p['types'][$post->type]) && $accounts->has($k));
        return view('posts.edit', compact('post', 'platforms'));
    }

    public function update(Request $request, Post $post)
    {
        $this->own($post);
        abort_unless($post->state === 'scheduled', 404);

        $data = $request->validate([
            'scheduled_at' => ['required', 'date', 'after:+1 minute'],
            'caption'      => ['nullable', 'string', 'max:63000'],
            'yt_title'     => ['nullable', 'string', 'max:100'],
            'targets'      => ['required', 'array', 'min:1'],
            'targets.*'    => ['in:'.implode(',', array_keys(config('posting.platforms')))],
        ], ['scheduled_at.after' => 'Jadwal minimal 1 menit dari sekarang.']);

        foreach ($data['targets'] as $pk) {
            $max = config("posting.platforms.$pk.caption_max");
            if ($pk !== 'youtube' && mb_strlen($data['caption'] ?? '') > $max) {
                return back()->withInput()->withErrors(['caption' => "Caption melebihi batas $max karakter untuk ".config("posting.platforms.$pk.label").'.']);
            }
            if ($pk === 'youtube' && trim($data['yt_title'] ?? ($post->yt_meta['title'] ?? '')) === '') {
                return back()->withInput()->withErrors(['yt_title' => 'Judul YouTube wajib diisi.']);
            }
        }

        DB::transaction(function () use ($post, $data) {
            $yt = $post->yt_meta ?? [];
            if (array_key_exists('yt_title', $data) && $data['yt_title'] !== null) $yt['title'] = $data['yt_title'];
            $post->update([
                'scheduled_at' => Carbon::parse($data['scheduled_at']),
                'caption'      => $data['caption'] ?? '',
                'yt_meta'      => $yt,
            ]);
            $post->targets()->whereNotIn('platform', $data['targets'])->delete();
            foreach ($data['targets'] as $pk) {
                $post->targets()->firstOrCreate(['platform' => $pk], ['status' => 'queued']);
            }
        });

        return redirect()->route('schedule.index')->with('ok', 'Jadwal diperbarui.');
    }

    public function cancel(Post $post)
    {
        $this->own($post);
        abort_unless($post->state === 'scheduled', 404);
        $post->update(['state' => 'canceled']);
        $post->targets()->update(['status' => 'canceled']);
        return redirect()->route('schedule.index')->with('ok', 'Jadwal dibatalkan.');
    }

    private function deleteFiles(Post $post): void
    {
        $dirs = collect($post->media)->map(fn ($m) => dirname($m['path']))->unique();
        foreach ($dirs as $d) Storage::disk('public')->deleteDirectory($d);
    }
<<<<<<< HEAD

    // confirm(), cabang schedule:
    $post->update(['state' => 'scheduled']);
    ScheduledPost::updateOrCreate(
        ['post_id' => $post->id],
        ['scheduled_at' => $post->scheduled_at, 'status' => 'pending', 'last_error' => null]
    );

    // update(), di dalam DB::transaction setelah $post->update([...]):
    ScheduledPost::where('post_id', $post->id)
        ->where('status', 'pending')
        ->update(['scheduled_at' => Carbon::parse($data['scheduled_at'])]);

    // cancel(), setelah $post->update(['state' => 'canceled']):
    ScheduledPost::where('post_id', $post->id)
        ->where('status', 'pending')
        ->update(['status' => 'canceled']);
=======
>>>>>>> bebel
}
