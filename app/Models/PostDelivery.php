<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostDelivery extends Model
{
    protected $fillable = ['post_id', 'platform', 'status', 'external_id', 'error', 'published_at'];
    protected $casts = ['published_at' => 'datetime'];

    public function post() { return $this->belongsTo(Post::class); }
}
