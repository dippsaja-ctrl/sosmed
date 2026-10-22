
<?php

use Illuminate\Support\Facades\Schedule;

/*
 * Scheduler Laravel. Di server, cukup 1 entri cron:
 *   * * * * * cd /path/ke/project && php artisan schedule:run >> /dev/null 2>&1
 * Saat development, jalankan: php artisan schedule:work
 */

// Cek posting terjadwal yang waktunya sudah tiba, lalu masukkan ke queue.
Schedule::command('posts:dispatch-due')
    ->everyMinute()
    ->withoutOverlapping();

// Perbarui token yang hampir kedaluwarsa.
Schedule::command('tokens:refresh')
    ->everyTenMinutes()
    ->withoutOverlapping();

// Perbarui token akun media sosial.
Schedule::command('social:refresh-tokens')
    ->everyFifteenMinutes()
    ->withoutOverlapping();

