<?php

namespace App\Http\Controllers;

use App\Jobs\PublishToPlatform;
use App\Models\Post;
use App\Models\SocialAccount;
<<<<<<< HEAD
use App\Models\ScheduledPost;
=======
>>>>>>> 645607949a780e1c0ec292546c23efdc46250fff
use App\Support\PostValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Pastikan posting milik pengguna yang sedang login.
     */
    private function own(Post $post): void
    {
        abort_unless((int) $post->user_id === (int) auth()->id(), 403);
    }

    /**
     * Ambil akun media sosial milik pengguna.
     */
    private function accounts()
    {
        return SocialAccount::where('user_id', auth()->id())
            ->get()
            ->keyBy('platform');
    }

    /**
     * Formulir posting baru.
     */
    public function create()
    {
        return view('posts.create', [
            'platforms' => config('posting.platforms'),
            'accounts'  => $this->accounts(),
        ]);
    }

    /**
     * Simpan posting sebagai draf.
     */
    public function store(Request $request)
    {
        $keys = implode(',', array_keys(config('posting.platforms', [])));

        $data = $request->validate([
            'type'           => ['required', 'in:video,carousel,photo'],
            'targets'        => ['required', 'array', 'min:1'],
            'targets.*'      => ["required", "in:$keys"],
            'caption'        => ['nullable', 'string', 'max:63000'],
            'media'          => ['required', 'array', 'min:1', 'max:35'],
            'media.*'        => [
                'required',
                'file',
                'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/quicktime',
                'max:2097152',
            ],
            'video_duration' => ['nullable', 'numeric', 'min:0'],
            'mode'           => ['required', 'in:now,schedule'],
            'scheduled_at'   => [
                'required_if:mode,schedule',
                'nullable',
                'date',
                'after:+1 minute',
            ],
            'yt.title'       => ['nullable', 'string', 'max:100'],
            'yt.desc'        => ['nullable', 'string', 'max:5000'],
            'yt.tags'        => ['nullable', 'string', 'max:500'],
            'yt.category'    => ['nullable', 'in:22,24,27,28,25'],
            'yt.visibility'  => ['nullable', 'in:private,unlisted,public'],
        ], [
            'scheduled_at.after' => 'Jadwal minimal 1 menit dari sekarang.',
            'media.*.mimetypes'  => 'Format file harus JPG, PNG, WEBP, MP4, atau MOV.',
        ]);

        // Pastikan seluruh akun tujuan sudah terhubung.
        $connected = $this->accounts()->keys()->all();
        $missing = array_diff($data['targets'], $connected);

        if (!empty($missing)) {
            return back()
                ->withInput()
                ->withErrors([
                    'targets' => 'Akun belum terhubung: '
                        . implode(', ', $missing),
                ]);
        }

        // Kumpulkan informasi setiap file media.
        $files = $request->file('media');
        $media = [];

        foreach ($files as $file) {
            $isVideo = str_starts_with(
                (string) $file->getMimeType(),
                'video/'
            );

            $dimensions = $isVideo
                ? false
                : @getimagesize($file->getRealPath());

            $media[] = [
                'name'     => $file->getClientOriginalName(),
                'kind'     => $isVideo ? 'video' : 'image',
                'mime'     => $file->getMimeType(),
                'size'     => $file->getSize(),
                'width'    => $dimensions ? $dimensions[0] : null,
                'height'   => $dimensions ? $dimensions[1] : null,
                'duration' => $isVideo && isset($data['video_duration'])
                    ? (int) round($data['video_duration'])
                    : null,
            ];
        }

        // Validasi aturan khusus setiap platform.
        $youtube = $request->input('yt', []);

        $errors = PostValidator::check(
            $data['type'],
            $data['targets'],
            $media,
            (string) ($data['caption'] ?? ''),
            $youtube
        );

        if ($errors) {
            return back()
                ->withInput()
                ->with('platform_errors', $errors);
        }

        // Simpan file media.
        $directory = 'posts/' . Str::uuid();

        foreach ($files as $index => $file) {
            $media[$index]['path'] = $file->store(
                $directory,
                'public'
            );
        }

        try {
            // Simpan data posting dan target secara bersamaan.
            $post = DB::transaction(function () use (
                $data,
                $media,
                $youtube
            ) {
                $post = Post::create([
                    'user_id'      => auth()->id(),
                    'type'         => $data['type'],
                    'caption'      => $data['caption'] ?? '',
                    'yt_meta'      => $youtube,
                    'media'        => $media,
                    'mode'         => $data['mode'],
                    'scheduled_at' => $data['mode'] === 'schedule'
                        ? Carbon::parse($data['scheduled_at'])
                        : null,
                    'state'        => 'draft',
                ]);

                foreach ($data['targets'] as $platform) {
                    $post->targets()->create([
                        'platform' => $platform,
                        'status'   => 'queued',
                    ]);
                }

                return $post;
            });
        } catch (\Throwable $exception) {
            // Hapus file jika penyimpanan database gagal.
            Storage::disk('public')->deleteDirectory($directory);

            throw $exception;
        }

        return redirect()->route('posts.preview', $post);
    }

    /**
     * Tampilkan pratinjau posting.
     */
    public function preview(Post $post)
    {
        $this->own($post);

        abort_unless($post->state === 'draft', 404);

        $post->load('targets');

        return view('posts.preview', [
            'post'      => $post,
            'platforms' => config('posting.platforms'),
            'accounts'  => $this->accounts(),
        ]);
    }

    /**
     * Konfirmasi posting atau jadwal.
     */
    public function confirm(Post $post)
    {
        $this->own($post);

        abort_unless($post->state === 'draft', 404);

        $post->load('targets');

        if ($post->mode === 'schedule') {
            if (
                !$post->scheduled_at ||
                $post->scheduled_at->lt(now()->addMinute())
            ) {
                return redirect()
                    ->route('posts.preview', $post)
                    ->withErrors([
                        'jadwal' => 'Waktu jadwal sudah lewat. '
                            . 'Buat ulang posting dengan jadwal baru.',
                    ]);
            }

            $post->update([
                'state' => 'scheduled',
            ]);

            return redirect()
                ->route('schedule.index')
                ->with('ok', 'Posting dijadwalkan dan akan terbit otomatis.');
        }

<<<<<<< HEAD
        $post->update(['state' => 'processing']);
        foreach ($post->targets as $target) {
            PublishToPlatform::dispatch($target, 1)->onQueue('uploads');
            PublishToPlatform::dispatch($target, 1);
        }
        return redirect()->route('history.index')->with('ok', 'Posting dikirim ke antrean. Status diperbarui otomatis di Riwayat.');
=======
        // Masukkan setiap target ke antrean upload.
        DB::transaction(function () use ($post) {
            $post->update([
                'state' => 'processing',
            ]);

            foreach ($post->targets as $target) {
                PublishToPlatform::dispatch($target, 1)
                    ->onQueue('uploads');
            }
        });

        return redirect()
            ->route('history.index')
            ->with(
                'ok',
                'Posting dikirim ke antrean. '
                . 'Status diperbarui otomatis di Riwayat.'
            );
>>>>>>> 645607949a780e1c0ec292546c23efdc46250fff
    }

    /**
     * Hapus draf beserta file medianya.
     */
    public function discard(Post $post)
    {
        $this->own($post);

        abort_unless($post->state === 'draft', 404);

        $this->deleteFiles($post);
        $post->delete();

        return redirect()
            ->route('posts.create')
            ->with('ok', 'Draf dibuang.');
    }

    /**
     * Formulir edit posting terjadwal.
     */
    public function edit(Post $post)
    {
        $this->own($post);

        abort_unless($post->state === 'scheduled', 404);

        $post->load('targets');

        $accounts = $this->accounts();

        $platforms = collect(config('posting.platforms'))
            ->filter(
                fn ($platform, $key) =>
                    isset($platform['types'][$post->type])
                    && $accounts->has($key)
            );

        return view('posts.edit', compact('post', 'platforms'));
    }

    /**
     * Perbarui jadwal dan informasi posting.
     */
    public function update(Request $request, Post $post)
    {
        $this->own($post);

        abort_unless($post->state === 'scheduled', 404);

        $data = $request->validate([
            'scheduled_at' => [
                'required',
                'date',
                'after:+1 minute',
            ],
            'caption'      => ['nullable', 'string', 'max:63000'],
            'yt_title'     => ['nullable', 'string', 'max:100'],
            'targets'      => ['required', 'array', 'min:1'],
            'targets.*'    => [
                'required',
                'in:' . implode(',', array_keys(
                    config('posting.platforms', [])
                )),
            ],
        ], [
            'scheduled_at.after' => 'Jadwal minimal 1 menit dari sekarang.',
        ]);

        $accounts = $this->accounts();

        foreach ($data['targets'] as $platform) {
            if (!$accounts->has($platform)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'targets' => "Akun $platform belum terhubung.",
                    ]);
            }

            $maximum = config(
                "posting.platforms.$platform.caption_max"
            );

            if (
                $platform !== 'youtube'
                && $maximum !== null
                && mb_strlen($data['caption'] ?? '') > $maximum
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'caption' => 'Caption melebihi batas '
                            . $maximum
                            . ' karakter untuk '
                            . config("posting.platforms.$platform.label"),
                    ]);
            }

            if (
                $platform === 'youtube'
                && trim(
                    $data['yt_title']
                    ?? ($post->yt_meta['title'] ?? '')
                ) === ''
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'yt_title' => 'Judul YouTube wajib diisi.',
                    ]);
            }
        }

        DB::transaction(function () use ($post, $data) {
            $youtube = $post->yt_meta ?? [];

            if (
                array_key_exists('yt_title', $data)
                && $data['yt_title'] !== null
            ) {
                $youtube['title'] = $data['yt_title'];
            }

            $post->update([
                'scheduled_at' => Carbon::parse($data['scheduled_at']),
                'caption'      => $data['caption'] ?? '',
                'yt_meta'      => $youtube,
            ]);

            // Hapus target yang tidak lagi dipilih.
            $post->targets()
                ->whereNotIn('platform', $data['targets'])
                ->delete();

            // Tambahkan target baru jika belum tersedia.
            foreach ($data['targets'] as $platform) {
                $post->targets()->firstOrCreate(
                    ['platform' => $platform],
                    ['status' => 'queued']
                );
            }
        });

        return redirect()
            ->route('schedule.index')
            ->with('ok', 'Jadwal diperbarui.');
    }

    /**
     * Batalkan posting terjadwal.
     */
    public function cancel(Post $post)
    {
        $this->own($post);

        abort_unless($post->state === 'scheduled', 404);

        DB::transaction(function () use ($post) {
            $post->update([
                'state' => 'canceled',
            ]);

            $post->targets()->update([
                'status' => 'canceled',
            ]);
        });

        return redirect()
            ->route('schedule.index')
            ->with('ok', 'Jadwal dibatalkan.');
    }

    /**
     * Hapus direktori file media milik posting.
     */
    private function deleteFiles(Post $post): void
    {
        $directories = collect($post->media ?? [])
            ->filter(fn ($media) => !empty($media['path']))
            ->map(fn ($media) => dirname($media['path']))
            ->unique();

        foreach ($directories as $directory) {
            Storage::disk('public')->deleteDirectory($directory);
        }
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
}
=======
}
>>>>>>> 645607949a780e1c0ec292546c23efdc46250fff
