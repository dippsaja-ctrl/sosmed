<?php

namespace App\Console\Commands;

use App\Jobs\PublishToPlatform;
use App\Models\Post;
use Illuminate\Console\Command;

class DispatchDuePosts extends Command
{
    protected $signature = 'posts:dispatch-due';
    protected $description = 'Kirim ke antrean semua posting terjadwal yang waktunya sudah tiba';

    public function handle(): int
    {
        $count = 0;
        Post::with('targets')->where('state', 'scheduled')->where('scheduled_at', '<=', now())

            ->eachById(function (Post $post) use (&$count) {

            ->each(function (Post $post) use (&$count) {

                // update atomik supaya tidak terkirim dua kali bila scheduler tumpang tindih
                $claimed = Post::whereKey($post->id)->where('state', 'scheduled')->update(['state' => 'processing']);
                if (! $claimed) return;

                foreach ($post->targets()->where('status', 'queued')->get() as $target) {
                    PublishToPlatform::dispatch($target, 3); // retry otomatis hingga 3 kali
                }
                $count++;
            });

        $this->info("Posting terjadwal diproses: $count");
        return self::SUCCESS;
    }
}
