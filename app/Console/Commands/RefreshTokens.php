<?php

namespace App\Console\Commands;

use App\Models\SocialAccount;
use App\Notifications\AppNotice;
use App\Services\Platforms\PlatformManager;
use Illuminate\Console\Command;
use Throwable;

class RefreshTokens extends Command
{
    protected $signature = 'tokens:refresh';
    protected $description = 'Perbarui access token yang hampir kedaluwarsa (refresh token otomatis)';

    public function handle(PlatformManager $platforms): int
    {
        SocialAccount::whereNotNull('expires_at')->with('user')->each(function (SocialAccount $a) use ($platforms) {
            if (! $a->expiresSoon() && ! $a->isExpired()) return;

            try {
                $platforms->for($a->platform)->refresh($a);
                $this->info("Token {$a->platform} diperbarui.");
            } catch (Throwable $e) {
                $this->error("Token {$a->platform} gagal diperbarui: ".$e->getMessage());
                $label = config("posting.platforms.{$a->platform}.label");
                $a->user->notify(new AppNotice('bad', "Token $label gagal diperbarui. Hubungkan ulang di menu Akun Sosmed."));
            }
        });

        return self::SUCCESS;
    }
}
