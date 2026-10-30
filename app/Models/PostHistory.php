<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostHistory extends Model
{
    protected $fillable = [
        'post_id',
        'post_target_id',
        'platform',
        'status',
        'attempt',
        'published_at',
        'error_message',
        'post_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'attempt' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(PostTarget::class, 'post_target_id');
    }

    public static function logSuccess(PostTarget $target, ?string $url = null): self
    {
        return static::create([
            'post_id' => $target->post_id,
            'post_target_id' => $target->id,
            'platform' => $target->platform,
            'status' => 'success',
            'attempt' => static::where('post_target_id', $target->id)->count() + 1,
            'published_at' => now(),
            'post_url' => $url ? mb_substr($url, 0, 500) : null,
        ]);
    }

    public static function logFailure(PostTarget $target, string $message): self
    {
        return static::create([
            'post_id' => $target->post_id,
            'post_target_id' => $target->id,
            'platform' => $target->platform,
            'status' => 'failed',
            'attempt' => static::where('post_target_id', $target->id)->count() + 1,
            'error_message' => mb_substr($message, 0, 2000),
        ]);
    }
}
