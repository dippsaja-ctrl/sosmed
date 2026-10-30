
@extends('layouts.app')

@section('title', 'Terjadwal')

@section('content')
<section class="card">
    <div class="row" style="align-items:center;justify-content:space-between;gap:12px">
        <div>
            <h2 style="margin:0 0 6px">Posting Terjadwal</h2>
            <p class="muted" style="margin:0">
                Pantau jadwal publikasi dan periksa status posting Anda.
            </p>
        </div>

        <a href="{{ route('posts.create') }}" class="btn primary">
    <span class="plus-icon">+</span>
    Buat posting
    </a>
    </div>
</section>

<section class="card">
    <h3 style="margin-top:0">Daftar Posting Terjadwal</h3>

    <form method="GET" action="{{ route('schedule.index') }}"
          id="scheduleFilter" class="form">
        <input type="hidden" name="view" value="list">

        <div class="field">
            <label for="scheduleSearch">Pencarian</label>
            <input
                id="scheduleSearch"
                type="search"
                name="q"
                value="{{ $keyword ?? request('q') }}"
                placeholder="Cari caption atau platform..."
                autocomplete="off"
                style="width:100%;min-height:52px;padding:12px 16px;border:1px solid #dbe3ee;border-radius:12px;font:inherit"
            >
        </div>

        <div class="field">
            <label for="scheduleStatus">Status</label>
            <select id="scheduleStatus" name="status"
                    style="width:100%;min-height:48px;padding:10px 14px;border:1px solid #dbe3ee;border-radius:12px;font:inherit">
                <option value="">Semua status</option>
                <option value="scheduled" @selected(($status ?? '') === 'scheduled')>Menunggu jadwal</option>
                <option value="processing" @selected(($status ?? '') === 'processing')>Sedang diproses</option>
                <option value="done" @selected(($status ?? '') === 'done')>Selesai</option>
                <option value="canceled" @selected(($status ?? '') === 'canceled')>Dibatalkan</option>
            </select>
        </div>

        <noscript><button class="btn primary" type="submit">Cari</button></noscript>
    </form>
</section>

<section class="card">
    <h3 style="margin-top:0">Jadwal Berikutnya</h3>

    @forelse($upcoming as $post)
        @include('partials.post-row', ['post' => $post, 'mode' => 'plain'])
    @empty
        <div class="empty">
            Belum ada posting yang menunggu publikasi.
        </div>
    @endforelse
</section>

<section class="card">
    <h3 style="margin-top:0">Jadwal Sebelumnya</h3>

    @forelse($past as $post)
        @include('partials.post-row', ['post' => $post, 'mode' => 'history'])
    @empty
        <div class="empty">
            Belum ada riwayat jadwal sebelumnya.
        </div>
    @endforelse
</section>

<script>
(function () {
    const form = document.getElementById('scheduleFilter');
    const input = document.getElementById('scheduleSearch');
    const status = document.getElementById('scheduleStatus');

    if (!form || !input || !status) return;

    let timer;
    let lastValue = input.value.trim();

    input.addEventListener('input', function () {
        clearTimeout(timer);

        const value = input.value.trim();

        if (value === lastValue) return;
        if (value.length > 0 && value.length < 2) return;

        timer = setTimeout(function () {
            lastValue = value;
            form.submit();
        }, 500);
    });

    input.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            clearTimeout(timer);
            form.submit();
        }
    });

    status.addEventListener('change', function () {
        form.submit();
    });
})();
</script>
@endsection
