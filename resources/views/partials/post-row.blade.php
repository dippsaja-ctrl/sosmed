@php
    [$label, $cls] = $post->overall();
    $first = $post->media[0] ?? null;
    $thumb = $first && $first['kind'] === 'image' ? \Illuminate\Support\Facades\Storage::disk('public')->url($first['path']) : null;
    $typeLabel = ['video' => 'Video / Reels', 'carousel' => 'Carousel', 'photo' => 'Foto'][$post->type];
@endphp
<div class="item">
    <span class="th">@if($thumb)<img src="{{ $thumb }}" alt="">@else {{ $post->type === 'video' ? 'Video' : 'Foto' }} @endif</span>
    <div class="body">
        <div class="row">
            <b>{{ ($post->scheduled_at ?? $post->created_at)->translatedFormat('j M Y, H:i') }}</b>
            <span class="badge {{ $cls }}">{{ $label }}</span>
            <span class="muted sm">{{ $typeLabel }}</span>
        </div>
        <div class="cap">{{ $post->title() }}</div>

        @if($mode === 'history')
            @foreach($post->targets as $t)
                @php [$tl, $tc] = $t->label(); @endphp
                <div class="tgt">
                    @include('partials.platform-chip', ['key' => $t->platform])
                    <span class="badge {{ $tc }}">{{ $tl }}@if($t->status === 'retrying') {{ $t->attempts }}/3 @endif</span>
                    @if($t->status === 'success' && $t->external_url)<a class="sm" href="{{ $t->external_url }}" target="_blank" rel="noopener">Lihat postingan</a>@endif
                    @if($t->status === 'failed')
                        <form method="POST" action="{{ route('history.retry', [$post, $t->platform]) }}">@csrf<button class="btn sm" type="submit">Coba lagi</button></form>
                    @endif
                    @if($t->error)<span class="err">{{ $t->error }}</span>@endif
                    @if($t->note && $t->status === 'success')<span class="note">{{ $t->note }}</span>@endif
                </div>
            @endforeach
            @if($post->targets->where('status', 'failed')->count() > 1)
                <form method="POST" action="{{ route('history.retry-all', $post) }}" style="margin-top:8px">@csrf<button class="btn sm" type="submit">Coba lagi semua yang gagal</button></form>
            @endif
        @else
            <div class="row" style="margin-top:6px">@foreach($post->targets as $t)@include('partials.platform-chip', ['key' => $t->platform])@endforeach</div>
        @endif

        @if($mode === 'schedule' && $post->state === 'scheduled')
            <div class="row" style="margin-top:8px">
                <a class="btn sm" href="{{ route('posts.edit', $post) }}">Edit</a>
                <form method="POST" action="{{ route('posts.cancel', $post) }}" onsubmit="return confirm('Batalkan jadwal ini? Posting tidak akan terbit.')">@csrf
                    <button class="btn sm danger" type="submit">Batalkan</button>
                </form>
            </div>
        @endif
    </div>
</div>
