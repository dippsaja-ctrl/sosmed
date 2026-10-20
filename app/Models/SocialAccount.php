<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class SocialAccount extends Model
{
    protected $fillable = [
        'user_id', 'platform', 'handle', 'external_id',
        'access_token', 'refresh_token', 'expires_at', 'connected_at',
    ];

    protected $hidden = ['access_token', 'refresh_token'];

    protected function casts(): array
    {
        return [
            'access_token'  => 'encrypted',   // otomatis terenkripsi di database
            'refresh_token' => 'encrypted',
            'expires_at'    => 'datetime',
            'connected_at'  => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /** Token dianggap "hampir habis" kalau sisa umurnya < 20% dari masa berlaku platform. */
    public function expiresSoon(): bool
    {
        $life = config("posting.platforms.{$this->platform}.token_life");
        if ($this->expires_at === null || ! $life) {
            return false;
        }
        return $this->expires_at->lt(now()->addSeconds((int) ($life * 0.2)));
    }

    public function scopeExpiring(Builder $query, int $minutes = 10): Builder
    {
        return $query->whereNotNull('refresh_token')
            ->whereNotNull('token_expires_at')
            ->where('needs_reauth', false)
            ->where('token_expires_at', '<=', now()->addMinutes($minutes));
    }

}
