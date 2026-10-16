
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{
    protected $fillable = ['post_id', 'scheduled_at', 'status', 'dispatched_at', 'last_error'];

    protected $casts = [
        'scheduled_at'  => 'datetime',
        'dispatched_at' => 'datetime',
    ];

    public function post() { return $this->belongsTo(Post::class); }

    public function scopeDue(Builder $q): Builder
    {
        return $q->where('status', 'pending')->where('scheduled_at', '<=', now());
    }
}