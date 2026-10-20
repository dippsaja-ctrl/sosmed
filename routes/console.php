
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

<<<<<<< HEAD
// Perbarui token yang hampir kedaluwarsa (refresh token otomatis).
Schedule::command('tokens:refresh')->everyTenMinutes()->withoutOverlapping();
=======
// Perbarui token yang hampir kedaluwarsa.
Schedule::command('tokens:refresh')
    ->everyTenMinutes()
    ->withoutOverlapping();
>>>>>>> 645607949a780e1c0ec292546c23efdc46250fff

// Perbarui token akun media sosial.
Schedule::command('social:refresh-tokens')
    ->everyFifteenMinutes()
    ->withoutOverlapping();
<<<<<<< HEAD

=======
>>>>>>> 645607949a780e1c0ec292546c23efdc46250fff
