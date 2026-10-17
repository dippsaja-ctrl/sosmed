{{-- butuh: $post, $platform, $accounts --}}
@php
    $p = config("posting.platforms.$platform");
    $urls = $post->mediaUrls();
    $first = $post->media[0] ?? null;
    $yt = $post->yt_meta ?? [];
@endphp
<div class="pv">
    <div class="pv-head">
        <span class="code xs" style="--c:{{ $p['color'] }}">{{ $p['short'] }}</span>
        <div><b>{{ $accounts[$platform]->handle ?? $p['label'] }}</b><small>Tampil sebagai {{ $p['types'][$post->type] ?? '-' }}</small></div>
    </div>
    <div class="pv-media">
        @if($first && $first['kind'] === 'video')
            <video src="{{ $urls[0] }}" controls preload="metadata"></video>
        @elseif($first)
            <img src="{{ $urls[0] }}" alt="Media pertama">
        @endif
        @if($post->type === 'carousel')<span class="pv-count">1/{{ count($urls) }}</span>@endif
    </div>
    @if($post->type === 'carousel' && count($urls) > 1)
        <div class="pv-strip">@foreach($urls as $u)<img src="{{ $u }}" alt="">@endforeach</div>
    @endif
    <div class="pv-body">
        @if($platform === 'youtube')
            <b>{{ $yt['title'] ?? '(belum ada judul)' }}</b>
            <p class="muted sm">Visibilitas: {{ ['private' => 'Privat', 'unlisted' => 'Tidak terdaftar', 'public' => 'Publik'][$yt['visibility'] ?? 'private'] }}</p>
            <p>{{ \Illuminate\Support\Str::limit(($yt['desc'] ?? '') ?: $post->caption, 140) }}</p>
        @else
            <p><b>{{ $accounts[$platform]->handle ?? $p['label'] }}</b> {{ \Illuminate\Support\Str::limit($post->caption, 125) ?: '(caption kosong)' }}</p>
        @endif
    </div>
</div>
