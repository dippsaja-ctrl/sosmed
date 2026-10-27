@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="row" style="margin-bottom:16px">
    <div><h2>Halo, {{ auth()->user()->name }}</h2><p class="muted" style="margin:0">Ringkasan hal yang perlu Anda cek hari ini.</p></div>
    <span class="sp"></span>
    <a href="{{ route('posts.create') }}" class="btn primary">
    <span style="font-size: 20px; font-weight: 400; line-height: 1;">+</span>
    Buat posting
</a>
</div>

<div class="grid2">
    <section class="card">
        <h3>Perlu perhatian</h3>
        @forelse($attention as [$cls, $text, $url, $btn])
            <div class="attn {{ $cls }}"><span>{{ $text }}</span><a class="btn sm" href="{{ $url }}">{{ $btn }}</a></div>
        @empty
            <div class="attn ok"><span>Semua akun terhubung dan tidak ada posting gagal.</span></div>
        @endforelse
    </section>
    <section class="card">
        <h3>Akan terbit berikutnya</h3>
        @forelse($next as $post)@include('partials.post-row', ['post' => $post, 'mode' => 'plain'])
        @empty<div class="empty">Belum ada jadwal. <a href="{{ route('posts.create') }}">Jadwalkan posting</a></div>@endforelse
    </section>
</div>

<section class="card" style="margin-top:14px">
    <h3>Status akun</h3>
    <div class="stamps">
        @foreach($platforms as $key => $p)
            @php $a = $accounts[$key] ?? null; @endphp
            <a class="stamp" style="--c:{{ $p['color'] }};text-decoration:none;color:inherit" href="{{ route('accounts.index') }}">
                <span class="row"><span class="code" style="--c:{{ $p['color'] }}">{{ $p['short'] }}</span>
                    @if(!$a)<span class="badge muted">Terputus</span>@elseif($a->isExpired())<span class="badge bad">Token habis</span>@else<span class="badge ok">Terhubung</span>@endif</span>
                <b>{{ $p['label'] }}</b><small>{{ $a->handle ?? 'Klik untuk menghubungkan' }}</small>
            </a>
        @endforeach
    </div>
</section>

<section class="card">
    <h3>Posting terakhir</h3>
    @forelse($recent as $post)@include('partials.post-row', ['post' => $post, 'mode' => 'history'])
    @empty<div class="empty">Belum ada posting.</div>@endforelse
</section>
@endsection
