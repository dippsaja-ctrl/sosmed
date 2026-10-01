<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TikTokAccount extends Model
{
    protected $table = 'tiktok_accounts';

    protected $fillable = [
        'user_id', 'open_id', 'display_name', 'avatar_url',
        'access_token', 'refresh_token',
        'access_token_expires_at', 'refresh_token_expires_at', 'scopes',
    ];

    protected $hidden = ['access_token', 'refresh_token'];

    protected function casts(): array
    {
        return [
            'access_token'             => 'encrypted',
            'refresh_token'            => 'encrypted',
            'access_token_expires_at'  => 'datetime',
            'refresh_token_expires_at' => 'datetime',
        ];
    }
}