<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\UploadedFile;

class PostMediaUploader
{
    /**
     * Simpan semua file berurutan. Urutan array = urutan slide carousel.
     *
     * @param  UploadedFile[]  $files
     */
    public function store(Post $post, array $files, string $disk = 'public'): void
    {
        foreach (array_values($files) as $i => $file) {
            $mime = $file->getMimeType();

            $post->media()->create([
                'disk'     => $disk,
                'path'     => $file->store("posts/{$post->id}", $disk),
                'mime'     => $mime,
                'type'     => str_starts_with($mime, 'video/') ? 'video' : 'image',
                'size'     => $file->getSize(),
                'position' => $i,
            ]);
        }
    }
}