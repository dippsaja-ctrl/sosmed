@extends('layouts.app')
@section('title', 'Terjadwal')
@section('content')
<div class="card">
    <div class="row">
        <div><b>Scheduler berjalan tiap menit</b>
            <p class="muted sm" style="margin:0">Cron server memanggil <code>php artisan schedule:run</code>, lalu posting yang waktunya tiba masuk ke antrean.</p></div>
    </div>
</div>

<div class="tabs">
    <a href="{{ route('schedule.index') }}" @class(['on' => $view === 'list'])>Daftar</a>
    <a href="{{ route('schedule.index', ['view' => 'cal']) }}" @class(['on' => $view === 'cal'])>Kalender</a>
</div>

@if($view === 'list')
    <section class="card">
        <h3>Akan terbit</h3>
        @forelse($upcoming as $post)@include('partials.post-row', ['post' => $post, 'mode' => 'schedule'])
        @empty<div class="empty">Belum ada posting terjadwal. <a href="{{ route('posts.create') }}">Buat jadwal baru</a></div>@endforelse
    </section>
    @if($past->isNotEmpty())
        <section class="card">
            <h3>Sudah lewat</h3>
            @foreach($past as $post)@include('partials.post-row', ['post' => $post, 'mode' => 'schedule'])@endforeach
        </section>
    @endif
@else
    <section class="card">
        <div class="row" style="margin-bottom:12px">
            <a class="btn sm" href="{{ route('schedule.index', ['view' => 'cal', 'm' => $prev]) }}" aria-label="Bulan sebelumnya">&larr;</a>
            <h3 style="margin:0;min-width:160px;text-align:center">{{ $month->translatedFormat('F Y') }}</h3>
            <a class="btn sm" href="{{ route('schedule.index', ['view' => 'cal', 'm' => $next]) }}" aria-label="Bulan berikutnya">&rarr;</a>
        </div>
        <div class="cal">
            @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $h)<div class="dh">{{ $h }}</div>@endforeach
            @foreach($days as $d)
                @php $k = $d->format('Y-m-d'); $items = $byDay[$k] ?? collect(); @endphp
                <a class="day {{ $d->month !== $month->month ? 'out' : '' }} {{ $d->isToday() ? 'today' : '' }} {{ $k === $selected ? 'sel' : '' }}"
                   href="{{ route('schedule.index', ['view' => 'cal', 'm' => $month->format('Y-m'), 'd' => $k]) }}">
                    <span class="dn">{{ $d->day }}</span>
                    @foreach($items->take(2) as $p)<span class="pill {{ $p->overall()[1] }}">{{ $p->scheduled_at->format('H:i') }} {{ \Illuminate\Support\Str::limit($p->title(), 10) }}</span>@endforeach
                    @if($items->count() > 2)<span class="pill muted">+{{ $items->count() - 2 }} lagi</span>@endif
                </a>
            @endforeach
        </div>
    </section>
    <section class="card">
        <h3>Jadwal pada {{ \Illuminate\Support\Carbon::parse($selected)->translatedFormat('j F Y') }}</h3>
        @forelse($byDay[$selected] ?? [] as $post)@include('partials.post-row', ['post' => $post, 'mode' => 'schedule'])
        @empty<div class="empty">Tidak ada jadwal di tanggal ini.</div>@endforelse
    </section>
@endif
@endsection
