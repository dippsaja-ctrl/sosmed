<?php

namespace App\Console\Commands;

use App\Models\SocialAccount;
use App\Services\SocialTokenService;
use Illuminate\Console\Command;

class RefreshSocialTokens extends Command
{
    protected $signature = 'social:refresh-tokens {--minutes=10 : Refresh token yang kedaluwarsa dalam N menit}';

    protected $description = 'Perbarui access_token sosmed yang kedaluwarsa memakai refresh_token';

    public function handle(SocialTokenService $service): int
    {
        $ok = 0;
        $failed = 0;

        SocialAccount::expiring((int) $this->option('minutes'))
            ->chunkById(100, function ($accounts) use ($service, &$ok, &$failed) {
                foreach ($accounts as $account) {
                    $service->refresh($account) ? $ok++ : $failed++;
                }
            });

        $this->info("Selesai. Berhasil: {$ok}, gagal: {$failed}");

        return self::SUCCESS;
    }
}