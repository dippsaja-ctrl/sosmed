<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostTarget extends Model
{
    protected $fillable = ['post_id', 'platform', 'status', 'attempts', 'external_url', 'note', 'error', 'published_at'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public const LABELS = [
        'queued' => ['Antre', 'info'], 'sending' => ['Mengirim', 'warn'], 'retrying' => ['Mencoba lagi', 'warn'],
        'success' => ['Terbit', 'ok'], 'failed' => ['Gagal', 'bad'], 'canceled' => ['Dibatalkan', 'muted'],
    ];

    public function label(): array
    {
        return self::LABELS[$this->status] ?? [$this->status, 'muted'];
    }
}
