<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PublishToSosmed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 600;

    public function __construct(
        public string $path,
        public ?string $caption = null,
    ) {}

    public function handle(): void
    {
        $disk = Storage::disk('sosmed_temp');

        // Cara 1: API butuh URL publik
        // $response = Http::withToken(config('services.sosmed.token'))
        //     ->post(config('services.sosmed.url').'/publish', [
        //         'media_url' => $disk->url($this->path),
        //         'caption'   => $this->caption,
        //     ]);

        // Cara 2: upload langsung via stream (hemat memori untuk video besar)
        $stream = $disk->readStream($this->path);

        $response = Http::withToken(config('services.sosmed.token'))
            ->attach('media', $stream, basename($this->path))
            ->post(config('services.sosmed.url').'/publish', [
                'caption' => $this->caption,
            ]);

        $response->throw();

        // Berhasil → hapus file sementara
        $disk->delete($this->path);
    }

    public function failed(\Throwable $e): void
    {
        // Setelah semua retry gagal, file dibiarkan untuk dibersihkan scheduler
        logger()->error('Gagal publish sosmed', ['path' => $this->path, 'error' => $e->getMessage()]);
    }
}