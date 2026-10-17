@extends('layouts.app')
@section('title', 'Buat posting')
@section('content')

@if(session('platform_errors'))
    <div class="flash bad" role="alert">
        <b>Konten belum memenuhi aturan platform:</b>
        @foreach(session('platform_errors') as $key => $list)
            <p style="margin:8px 0 0"><b>{{ $key === 'umum' ? 'Umum' : ($platforms[$key]['label'] ?? $key) }}</b></p>
            <ul>@foreach($list as $msg)<li>{{ $msg }}</li>@endforeach</ul>
        @endforeach
        <p class="sm" style="margin-top:8px">Pilih ulang file media setelah memperbaiki masalah di atas.</p>
    </div>
@endif

<form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" id="postForm" class="composer">
@csrf
<div>
    <section class="card">
        <h3>Jenis konten</h3>
        <div class="seg">
            @foreach(['video' => ['Video / Reels', 'Satu video, vertikal disarankan'], 'carousel' => ['Carousel', '2 foto atau lebih, berurutan'], 'photo' => ['Foto', 'Satu foto']] as $k => $v)
                <label class="seg-item"><input type="radio" name="type" value="{{ $k }}" @checked(old('type', 'video') === $k)><b>{{ $v[0] }}</b><small>{{ $v[1] }}</small></label>
            @endforeach
        </div>
    </section>

    <section class="card">
        <h3>Platform tujuan</h3>
        <div class="stamps">
            @foreach($platforms as $key => $p)
                <label class="stamp" style="--c:{{ $p['color'] }}" data-platform="{{ $key }}"
                       data-connected="{{ isset($accounts[$key]) ? 1 : 0 }}" data-types="{{ implode(',', array_keys($p['types'])) }}">
                    <input type="checkbox" name="targets[]" value="{{ $key }}" @checked(in_array($key, old('targets', []))) @disabled(!isset($accounts[$key]))>
                    <span class="code" style="--c:{{ $p['color'] }}">{{ $p['short'] }}</span>
                    <b>{{ $p['label'] }}</b>
                    <small class="stamp-note">{{ isset($accounts[$key]) ? '' : 'Belum terhubung' }}</small>
                </label>
            @endforeach
        </div>
        @if($accounts->count() < count($platforms))
            <p class="hint">Platform yang belum terhubung tidak bisa dipilih. <a href="{{ route('accounts.index') }}">Hubungkan di Akun Sosmed</a>.</p>
        @endif
    </section>

    <section class="card">
        <h3 id="mediaTitle">Media</h3>
        <label class="drop" id="drop" for="picker">
            <b id="dropText">Pilih atau seret file ke sini</b><br>
            <span class="muted sm">Format: JPG, PNG, WEBP, MP4, MOV.</span>
        </label>
        <input type="file" id="picker" hidden>
        {{-- input inilah yang dikirim ke server; isinya diatur JavaScript agar urutan slide sesuai --}}
        <input type="file" name="media[]" id="media" multiple hidden>
        <input type="hidden" name="video_duration" id="video_duration">
        <ul class="mlist" id="mlist"></ul>
    </section>

    <section class="card">
        <h3>Caption</h3>
        <textarea class="in" name="caption" id="caption" rows="5" placeholder="Tulis caption yang sama untuk semua platform">{{ old('caption') }}</textarea>
        <div class="hint" id="capCount"></div>
    </section>

    <section class="card hidden" id="ytBox">
        <h3>Detail YouTube</h3>
        <div class="field"><label class="f" for="yt_title">Judul</label><input class="in" id="yt_title" name="yt[title]" value="{{ old('yt.title') }}" maxlength="100"></div>
        <div class="field"><label class="f" for="yt_desc">Deskripsi</label><textarea class="in" id="yt_desc" name="yt[desc]" rows="3">{{ old('yt.desc') }}</textarea></div>
        <div class="field"><label class="f" for="yt_tags">Tags</label><input class="in" id="yt_tags" name="yt[tags]" value="{{ old('yt.tags') }}" placeholder="pisahkan dengan koma"></div>
        <div class="grid2" style="gap:12px">
            <div><label class="f" for="yt_cat">Kategori</label>
                <select class="in" id="yt_cat" name="yt[category]">
                    @foreach(['22' => 'Orang dan Blog', '24' => 'Hiburan', '27' => 'Pendidikan', '28' => 'Sains dan Teknologi', '25' => 'Berita dan Politik'] as $v => $l)
                        <option value="{{ $v }}" @selected(old('yt.category', '22') === (string) $v)>{{ $l }}</option>
                    @endforeach
                </select></div>
            <div><label class="f" for="yt_vis">Visibilitas</label>
                <select class="in" id="yt_vis" name="yt[visibility]">
                    @foreach(['private' => 'Privat', 'unlisted' => 'Tidak terdaftar', 'public' => 'Publik'] as $v => $l)
                        <option value="{{ $v }}" @selected(old('yt.visibility', 'private') === $v)>{{ $l }}</option>
                    @endforeach
                </select></div>
        </div>
        <p class="hint">Visibilitas publik baru aktif setelah audit Google disetujui.</p>
    </section>

    <section class="card">
        <h3>Waktu posting</h3>
        <div class="seg" style="margin-bottom:12px">
            <label class="seg-item"><input type="radio" name="mode" value="now" @checked(old('mode', 'now') === 'now')><b>Posting sekarang</b><small>Dikirim setelah Anda konfirmasi</small></label>
            <label class="seg-item"><input type="radio" name="mode" value="schedule" @checked(old('mode') === 'schedule')><b>Jadwalkan</b><small>Terbit otomatis lewat scheduler</small></label>
        </div>
        <div class="field hidden" id="whenBox">
            <label class="f" for="scheduled_at">Tanggal dan jam terbit</label>
            <input class="in" type="datetime-local" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}" min="{{ now()->addMinute()->format('Y-m-d\TH:i') }}">
            <p class="hint">Zona waktu server: {{ config('app.timezone') }}.</p>
        </div>
    </section>

    <div class="row"><button class="btn primary" type="submit">Periksa dan pratinjau</button></div>
</div>

<aside>
    <section class="card">
        <h3>Aturan platform (contoh)</h3>
        <div id="rules"></div>
        <p class="hint">Server memeriksa ulang semua aturan ini saat Anda menekan Periksa dan pratinjau. Cek dokumentasi resmi untuk batas terbaru.</p>
    </section>
</aside>
</form>
@endsection

@push('scripts')
<script>
(function () {
    const RULES = @json($platforms);
    const form = document.getElementById('postForm');
    const picker = document.getElementById('picker');
    const input = document.getElementById('media');
    const list = document.getElementById('mlist');
    const drop = document.getElementById('drop');
    let files = [];

    const type = () => form.querySelector('[name=type]:checked').value;
    const checked = () => [...form.querySelectorAll('[name="targets[]"]:checked')].map(i => i.value);
    const fmt = b => b >= 1048576 ? (b / 1048576).toFixed(1) + ' MB' : Math.max(1, Math.round(b / 1024)) + ' KB';
    const esc = s => s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    function applyType() {
        const t = type();
        picker.accept = t === 'video' ? 'video/*' : 'image/*';
        picker.multiple = t === 'carousel';
        document.getElementById('mediaTitle').textContent = t === 'video' ? 'Video' : t === 'photo' ? 'Foto' : 'Slide (urutan tampil)';
        document.getElementById('dropText').textContent = t === 'video' ? 'Pilih atau seret video ke sini' : 'Pilih atau seret foto ke sini';
        // kosongkan media jika jenisnya tidak cocok lagi
        files = files.filter(f => (t === 'video') === f.type.startsWith('video/'));
        if (t !== 'carousel') files = files.slice(0, 1);
        // platform yang tidak mendukung jenis ini dinonaktifkan
        form.querySelectorAll('.stamp').forEach(s => {
            const ok = s.dataset.connected === '1' && s.dataset.types.split(',').includes(t);
            const cb = s.querySelector('input');
            cb.disabled = !ok;
            if (!ok) cb.checked = false;
            s.classList.toggle('off', !ok);
            s.querySelector('.stamp-note').textContent = s.dataset.connected !== '1' ? 'Belum terhubung'
                : !ok ? 'Tidak mendukung jenis ini' : 'Tampil sebagai ' + RULES[s.dataset.platform].types[t];
        });
        sync();
    }

    function sync() {
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        input.files = dt.files;
        renderList();
        const vid = type() === 'video' && files[0];
        document.getElementById('video_duration').value = '';
        if (vid) {
            const v = document.createElement('video');
            v.preload = 'metadata';
            v.onloadedmetadata = () => { document.getElementById('video_duration').value = Math.round(v.duration); URL.revokeObjectURL(v.src); };
            v.src = URL.createObjectURL(files[0]);
        }
        renderRules();
    }

    function renderList() {
        const t = type();
        list.innerHTML = files.map((f, i) => {
            const thumb = f.type.startsWith('image/') ? '<img src="' + URL.createObjectURL(f) + '" alt="">' : 'Video';
            return '<li>' + (t === 'carousel' ? '<span class="num">' + (i + 1) + '</span>' : '') +
                '<span class="th">' + thumb + '</span><span class="meta"><b>' + esc(f.name) + '</b><span class="muted sm">' + fmt(f.size) + '</span></span>' +
                (t === 'carousel' ? '<button type="button" class="btn sm" data-up="' + i + '"' + (i === 0 ? ' disabled' : '') + ' aria-label="Naikkan">&uarr;</button>' +
                    '<button type="button" class="btn sm" data-down="' + i + '"' + (i === files.length - 1 ? ' disabled' : '') + ' aria-label="Turunkan">&darr;</button>' : '') +
                '<button type="button" class="btn sm" data-del="' + i + '" aria-label="Hapus">&times;</button></li>';
        }).join('');
    }

    function renderRules() {
        const t = type(), sel = checked();
        const box = document.getElementById('rules');
        if (!sel.length) { box.innerHTML = '<p class="muted">Pilih platform untuk melihat aturannya.</p>'; return; }
        box.innerHTML = sel.map(k => {
            const r = RULES[k], bits = [];
            if (t === 'video') { bits.push('Video maks ' + r.video_mb + ' MB'); bits.push('Durasi ' + r.video_sec[0] + ' sampai ' + r.video_sec[1] + ' detik'); }
            else { if (r.photo_mb) bits.push('Foto maks ' + r.photo_mb + ' MB'); if (t === 'carousel' && r.slides) bits.push(r.slides[0] + ' sampai ' + r.slides[1] + ' slide'); if (r.ratio) bits.push('Rasio ' + r.ratio[0] + ' sampai ' + r.ratio[1]); if (r.jpeg_only) bits.push('Hanya JPEG'); }
            bits.push('Caption maks ' + r.caption_max + ' karakter');
            return '<p><b>' + r.label + '</b><br><span class="muted sm">' + bits.join(', ') + '</span></p>';
        }).join('');
        document.getElementById('ytBox').classList.toggle('hidden', !sel.includes('youtube'));
        const lim = Math.min(...sel.filter(k => k !== 'youtube').map(k => RULES[k].caption_max), 2200);
        document.getElementById('capCount').textContent = document.getElementById('caption').value.length + ' / ' + lim;
    }

    function add(list2) {
        const t = type();
        const ok = list2.filter(f => (t === 'video') === f.type.startsWith('video/'));
        if (ok.length !== list2.length) alert(t === 'video' ? 'Pilih file video.' : 'Pilih file gambar.');
        files = t === 'carousel' ? files.concat(ok) : ok.slice(0, 1);
        sync();
    }

    picker.addEventListener('change', e => { add([...e.target.files]); picker.value = ''; });
    drop.addEventListener('dragover', e => { e.preventDefault(); drop.classList.add('over'); });
    drop.addEventListener('dragleave', () => drop.classList.remove('over'));
    drop.addEventListener('drop', e => { e.preventDefault(); drop.classList.remove('over'); add([...e.dataTransfer.files]); });
    list.addEventListener('click', e => {
        const b = e.target.closest('button'); if (!b) return;
        const swap = (i, j) => { [files[i], files[j]] = [files[j], files[i]]; sync(); };
        if (b.dataset.up) swap(+b.dataset.up, +b.dataset.up - 1);
        if (b.dataset.down) swap(+b.dataset.down, +b.dataset.down + 1);
        if (b.dataset.del) { files.splice(+b.dataset.del, 1); sync(); }
    });
    form.querySelectorAll('[name=type]').forEach(r => r.addEventListener('change', applyType));
    form.querySelectorAll('[name="targets[]"]').forEach(c => c.addEventListener('change', renderRules));
    document.getElementById('caption').addEventListener('input', renderRules);
    form.querySelectorAll('[name=mode]').forEach(r => r.addEventListener('change', () => {
        const s = form.querySelector('[name=mode]:checked').value === 'schedule';
        document.getElementById('whenBox').classList.toggle('hidden', !s);
        document.getElementById('scheduled_at').required = s;
    }));
    form.querySelector('[name=mode]:checked').dispatchEvent(new Event('change'));
    applyType();
})();
</script>
@endpush
