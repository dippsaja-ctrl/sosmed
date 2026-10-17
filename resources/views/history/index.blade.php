@extends('layouts.app')
@section('title', 'Riwayat posting')
@section('content')
<form method="GET" class="row" style="margin-bottom:14px">
    <label class="f" for="f" style="margin:0">Tampilkan</label>
    <select class="in" id="f" name="f" style="width:auto" onchange="this.form.submit()">
        <option value="all" @selected($filter === 'all')>Semua</option>
        <option value="ok" @selected($filter === 'ok')>Berhasil di semua platform</option>
        <option value="bad" @selected($filter === 'bad')>Ada yang gagal</option>
    </select>
    <noscript><button class="btn sm" type="submit">Terapkan</button></noscript>
    <span class="muted sm">Halaman ini memuat ulang otomatis selama ada posting yang sedang diproses.</span>
</form>

<section class="card">
    @forelse($posts as $post)@include('partials.post-row', ['post' => $post, 'mode' => 'history'])
    @empty<div class="empty">Belum ada riwayat untuk filter ini. <a href="{{ route('posts.create') }}">Buat posting</a></div>@endforelse
</section>

@if($posts->contains(fn ($p) => $p->state === 'processing'))
    @push('scripts')<script>setTimeout(() => location.reload(), 5000);</script>@endpush
@endif
@endsection
