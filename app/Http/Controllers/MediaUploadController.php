<?php

namespace App\Http\Controllers;

use App\Jobs\PublishToSosmed;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaUploadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'media'   => ['required', 'file',
                          'mimetypes:image/jpeg,image/png,video/mp4,video/quicktime',
                          'max:204800'], // 200 MB (dalam KB)
            'caption' => ['nullable', 'string', 'max:2200'],
        ]);

        $file = $request->file('media');

        // Nama acak agar aman dari path traversal & bentrok nama
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

        $path = $file->storeAs('uploads/'.now()->format('Y/m/d'), $filename, 'sosmed_temp');

        PublishToSosmed::dispatch($path, $validated['caption'] ?? null);

        return response()->json([
            'message' => 'File diterima, sedang diproses.',
            'path'    => $path,
        ], 202);
    }
}