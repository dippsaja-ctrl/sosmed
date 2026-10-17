<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable = ['user_id', 'type', 'caption', 'yt_meta', 'media', 'mode', 'scheduled_at', 'state'];

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

    /** URL publik tiap media (Instagram, Facebook, TikTok foto mengambil media lewat URL). */
    public function mediaUrls(): array
    {
        return collect($this->media)->map(fn ($m) => Storage::disk('public')->url($m['path']))->all();
    }

    /** Path file di server (dipakai untuk upload langsung: TikTok & YouTube). */
    public function mediaPaths(): array
    {
        return collect($this->media)->map(fn ($m) => Storage::disk('public')->path($m['path']))->all();
    }

    public function title(): string
    {
        return $this->caption ?: ($this->yt_meta['title'] ?? '(tanpa caption)');
    }

<<<<<<< HEAD
    public function media()      
    { 
        return $this->hasMany(PostMedia::class)->orderBy('position'); 
    }

    public function deliveries() 
    {
        return $this->hasMany(PostDelivery::class); 
    }

    public function histories() 
    { 
        return $this->hasMany(PostHistory::class); 
    }

=======
>>>>>>> bebel
    /** @return array{0:string,1:string} [label, kelas css] */
    public function overall(): array
    {
        if ($this->state === 'canceled') return ['Dibatalkan', 'muted'];
        if ($this->state === 'draft') return ['Draf', 'muted'];
        if ($this->state === 'scheduled') return ['Menunggu jadwal', 'info'];

        $s = $this->targets->pluck('status');
        if ($s->contains(fn ($x) => in_array($x, ['queued', 'sending', 'retrying']))) return ['Diproses', 'warn'];
        $ok = $s->filter(fn ($x) => $x === 'success')->count();
        if ($ok === $s->count()) return ['Berhasil', 'ok'];
        return $ok === 0 ? ['Gagal', 'bad'] : ['Sebagian gagal', 'warn'];
    }
}
