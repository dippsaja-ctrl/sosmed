
@extends('layouts.app')

@section('title', 'Riwayat')

@section('content')
<section class="card">
    <div class="row" style="align-items:center;justify-content:space-between;gap:12px">
        <div>
            <h2 style="margin:0 0 6px">Riwayat Publikasi</h2>
            <p class="muted" style="margin:0">
                Periksa hasil publikasi posting media sosial Anda.
            </p>
        </div>

        <a href="{{ route('posts.create') }}" class="btn primary">
    <span class="plus-icon">+</span>
    Buat posting
        </a>
    </div>
</section>

<section class="card">
    <h3 style="margin-top:0">Cari Riwayat Posting</h3>

    <form method="GET" action="{{ route('history.index') }}"
          id="historyFilter" class="form">
        <div class="field">
            <label for="historySearch">Pencarian</label>
            <input
                id="historySearch"
                type="search"
                name="q"
                value="{{ $keyword ?? request('q') }}"
                placeholder="Cari caption atau platform..."
                autocomplete="off"
                style="width:100%;min-height:52px;padding:12px 16px;border:1px solid #dbe3ee;border-radius:12px;font:inherit"
            >
        </div>

        <div class="field">
            <label for="historyStatus">Hasil publikasi</label>
            <select
                id="historyStatus"
                name="f"
                style="width:100%;min-height:48px;padding:10px 14px;border:1px solid #dbe3ee;border-radius:12px;font:inherit"
            >
                <option value="all" @selected(($filter ?? request('f', 'all')) === 'all')>Semua</option>
                <option value="ok" @selected(($filter ?? '') === 'ok')>Berhasil</option>
                <option value="bad" @selected(($filter ?? '') === 'bad')>Ada yang gagal</option>
            </select>
        </div>

        <noscript><button class="btn primary" type="submit">Cari</button></noscript>
    </form>
</section>

<section class="card">
    <h3 style="margin-top:0">Hasil Publikasi</h3>

    <div id="historyResults">
        @forelse($posts as $post)
            @include('partials.post-row', ['post' => $post, 'mode' => 'history'])
        @empty
            <div class="empty" id="historyEmpty">
                Belum ada riwayat yang sesuai dengan pencarian ini.
            </div>
        @endforelse
    </div>
</section>

<script>
(function () {
    const form = document.getElementById('historyFilter');
    const input = document.getElementById('historySearch');
    const filter = document.getElementById('historyStatus');

    if (!form || !input || !filter) return;

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

    filter.addEventListener('change', function () {
        form.submit();
    });
})();
</script>
@endsection
