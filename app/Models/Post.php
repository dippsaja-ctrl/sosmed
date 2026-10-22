<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'caption',
        'yt_meta',
        'media',
        'mode',
        'scheduled_at',
        'state',
    ];

    protected function casts(): array
    {
        return [
            'yt_meta'      => 'array',
            'media'        => 'array',
            'scheduled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function targets(): HasMany
    {
        return $this->hasMany(PostTarget::class);
    }

    /**
     * URL publik untuk setiap media.
     */
    public function mediaUrls(): array
    {
        return collect($this->media)
            ->map(fn ($m) => Storage::disk('public')->url($m['path']))
            ->all();
    }

    /**
     * Path file media di server.
     */
    public function mediaPaths(): array
    {
        return collect($this->media)
            ->map(fn ($m) => Storage::disk('public')->path($m['path']))
            ->all();
    }

    public function title(): string
    {
        return $this->caption
            ?: ($this->yt_meta['title'] ?? '(tanpa caption)');
    }

    public function media(): HasMany
    {
        return $this->hasMany(PostMedia::class)
            ->orderBy('position');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(PostDelivery::class);
    }

    /** @return array{0:string,1:string} [label, kelas css] */

    public function histories(): HasMany
    {
        return $this->hasMany(PostHistory::class);
    }

    /**
     * Menghasilkan label status dan kelas CSS.
     *
     * @return array{0:string,1:string}
     */

    public function overall(): array
    {
        if ($this->state === 'canceled') {
            return ['Dibatalkan', 'muted'];
        }

        if ($this->state === 'draft') {
            return ['Draf', 'muted'];
        }

        if ($this->state === 'scheduled') {
            return ['Menunggu jadwal', 'info'];
        }

        $statuses = $this->targets->pluck('status');

        if ($statuses->contains(
            fn ($status) => in_array(
                $status,
                ['queued', 'sending', 'retrying']
            )
        )) {
            return ['Diproses', 'warn'];
        }

        $successCount = $statuses
            ->filter(fn ($status) => $status === 'success')
            ->count();

        if ($successCount === $statuses->count()) {
            return ['Berhasil', 'ok'];
        }

        return $successCount === 0
            ? ['Gagal', 'bad']
            : ['Sebagian gagal', 'warn'];
    }
}
