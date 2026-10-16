@extends('layouts.app')
@section('title', 'Akun Sosmed')
@section('content')
<p class="muted">Hubungkan satu akun per platform. Aplikasi hanya meminta izin untuk memposting, bukan membaca pesan pribadi.</p>
<div class="grid2">
@foreach($platforms as $key => $p)
    @php $a = $accounts[$key] ?? null; @endphp
    <article class="card">
        <div class="row">
            <span class="code" style="--c:{{ $p['color'] }}">{{ $p['short'] }}</span>
            <div><h3 style="margin:0">{{ $p['label'] }}</h3><p class="muted sm" style="margin:0">{{ $a->handle ?? 'Belum terhubung' }}</p></div>
            <span class="sp"></span>
            @if(!$a)<span class="badge muted">Terputus</span>
            @elseif($a->isExpired())<span class="badge bad">Token kedaluwarsa</span>
            @else<span class="badge ok">Terhubung</span>@endif
        </div>

        @if($a)
            <dl class="kv">
                <dt>Terhubung sejak</dt><dd>{{ $a->connected_at?->translatedFormat('j M Y, H:i') ?? '-' }}</dd>
                <dt>Access token</dt>
                <dd>@if($a->expires_at === null) tidak memiliki masa berlaku @elseif($a->isExpired()) kedaluwarsa @else berlaku {{ $a->expires_at->diffForHumans(null, true) }} lagi @endif</dd>
            </dl>
            <div class="row">
                @if($a->refresh_token || $key === 'instagram')
                    <form method="POST" action="{{ route('accounts.refresh', $key) }}">@csrf<button class="btn sm" type="submit">Segarkan token</button></form>
                @endif
                <a class="btn sm" href="{{ route('oauth.redirect', $key) }}">Hubungkan ulang</a>
                <form method="POST" action="{{ route('accounts.destroy', $key) }}" onsubmit="return confirm('Putuskan koneksi {{ $p['label'] }}?')">@csrf @method('DELETE')
                    <button class="btn sm danger" type="submit">Putuskan koneksi</button>
                </form>
            </div>
        @else
            <p class="muted sm" style="margin-top:12px">Anda akan diarahkan ke halaman resmi {{ $p['label'] }} untuk login dan menekan Izinkan.</p>
            <a class="btn primary" href="{{ route('oauth.redirect', $key) }}">Hubungkan</a>
        @endif

        @if(in_array($key, ['tiktok', 'youtube']))
            <p class="hint">Postingan publik menunggu audit {{ $key === 'tiktok' ? 'TikTok' : 'Google' }} yang diurus kantor. Sebelum itu hasilnya privat.</p>
        @endif
    </article>
@endforeach
</div>
@endsection
