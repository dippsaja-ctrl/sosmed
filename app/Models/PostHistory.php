// app/Models/PostHistory.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostHistory extends Model
{
    protected $fillable = [
        'post_id', 'post_target_id', 'platform', 'status',
        'attempt', 'published_at', 'error_message', 'post_url',
    ];

    protected $casts = ['published_at' => 'datetime'];

    public function post()   { return $this->belongsTo(Post::class); }
    public function target() { return $this->belongsTo(PostTarget::class, 'post_target_id'); }

    public static function logSuccess(PostTarget $t, ?string $url = null): self
    {
        return static::create([
            'post_id'        => $t->post_id,
            'post_target_id' => $t->id,
            'platform'       => $t->platform,
            'status'         => 'success',
            'attempt'        => static::where('post_target_id', $t->id)->count() + 1,
            'published_at'   => now(),
            'post_url'       => $url,
        ]);
    }

    public static function logFailure(PostTarget $t, string $message): self
    {
        return static::create([
            'post_id'        => $t->post_id,
            'post_target_id' => $t->id,
            'platform'       => $t->platform,
            'status'         => 'failed',
            'attempt'        => static::where('post_target_id', $t->id)->count() + 1,
            'error_message'  => mb_substr($message, 0, 2000),
        ]);
    }
}