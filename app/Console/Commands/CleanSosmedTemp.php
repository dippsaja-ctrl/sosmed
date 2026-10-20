<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanSosmedTemp extends Command
{
    protected $signature = 'sosmed:clean-temp {--hours=24}';
    protected $description = 'Hapus file upload sementara yang lebih tua dari N jam';

    public function handle(): int
    {
        $disk   = Storage::disk('sosmed_temp');
        $limit  = now()->subHours((int) $this->option('hours'))->timestamp;
        $count  = 0;

        foreach ($disk->allFiles('uploads') as $file) {
            if ($disk->lastModified($file) < $limit) {
                $disk->delete($file);
                $count++;
            }
        }

        $this->info("{$count} file dihapus.");
        return self::SUCCESS;
    }
}