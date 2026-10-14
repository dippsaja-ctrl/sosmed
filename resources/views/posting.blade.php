@extends('layouts.app')
@section('title', 'Buat Postingan')
@section('breadcrumb', 'Buat Postingan')
@section('content')
<div class="page-head"><div><div class="eyebrow">Konten baru</div><h1>Buat Postingan</h1><p class="muted">Formulir untuk menyusun konten multi-platform.</p></div></div>
<div class="grid composer-grid">
    <article class="card">
        <div class="card-head"><div><h2>Informasi Postingan</h2><p class="muted small">Lengkapi konten yang akan dipublikasikan</p></div><span class="tag tag-blue">Draft</span></div>
        <div class="field"><label>Pilih platform</label><div class="check-row"><span class="check-chip">☑ Instagram</span><span class="check-chip">☑ Facebook</span><span class="check-chip">☐ TikTok</span><span class="check-chip">☐ YouTube</span></div></div>
        <div class="field"><label>Jenis konten</label><div class="check-row"><span class="check-chip">● Foto</span><span class="check-chip">○ Video</span><span class="check-chip">○ Carousel</span></div></div>
        <div class="field"><label>Caption postingan</label><textarea placeholder="Tulis caption untuk postingan di sini..."></textarea><div class="muted small align-right">0 / 2.200 karakter</div></div>
        <div class="field"><label>Media</label><div class="upload-box"><div class="upload-symbol">＋</div><strong>Tambahkan foto atau video</strong><span class="small">Area tampilan unggah media · JPG, PNG, MP4</span></div></div>
        <div class="field"><label>Waktu publikasi</label><div class="check-row"><span class="check-chip">● Publikasikan sekarang</span><span class="check-chip">○ Jadwalkan</span></div></div>
        <div class="button-row"><button class="btn btn-light">Simpan Draft</button><button class="btn btn-primary">➤ Publikasikan</button></div>
    </article>
    <aside class="card">
        <div class="card-head"><div><h2>Pratinjau Postingan</h2><p class="muted small">Gambaran tampilan konten</p></div></div>
        <div class="preview"><div class="platform"><span class="platform-logo ig">IG</span><div class="platform-info"><strong>Kantor Official</strong><small>@kantor.official</small></div><span class="muted">•••</span></div><div class="preview-image">PROMO<br>AKHIR BULAN</div><strong>Promo akhir bulan ✨</strong><p class="muted small preview-caption">Nikmati penawaran spesial untuk kamu. Jangan sampai terlewat!</p><div class="preview-line"></div><div class="preview-line short"></div><div class="muted small preview-actions">♡　♥　♧　↗</div></div>
        <div class="checklist"><h3>Checklist konten</h3><p class="muted small">• Platform tujuan dipilih</p><p class="muted small">• Caption sudah disiapkan</p><p class="muted small">• Media sudah tersedia</p></div>
    </aside>
</div>
@endsection
