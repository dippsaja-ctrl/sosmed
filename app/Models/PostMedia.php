<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    protected $table = 'post_media';
    protected $fillable = ['post_id', 'disk', 'path', 'mime', 'type', 'size', 'position'];

    public function isVideo(): bool { return $this->type === 'video'; }
    public function fullPath(): string { return Storage::disk($this->disk)->path($this->path); }
    public function publicUrl(): string { return Storage::disk($this->disk)->url($this->path); }
}
