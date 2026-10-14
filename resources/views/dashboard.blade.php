@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('content')
<div class="page-head">
    <div><div class="eyebrow">Ringkasan workspace</div><h1>Dashboard</h1><p class="muted">Pantau akun, postingan, dan aktivitas media sosial dari satu tempat.</p></div>
    <a class="btn btn-primary" href="{{ route('posting') }}">＋ Buat Postingan</a>
</div>

<div class="grid stats">
    <article class="card"><div class="stat-top"><span class="muted small">Total Akun</span><span class="stat-icon tint-blue">↗</span></div><div class="stat-number">4</div><div class="stat-note">Akun media sosial terdaftar</div></article>
    <article class="card"><div class="stat-top"><span class="muted small">Postingan Terkirim</span><span class="stat-icon tint-green">✓</span></div><div class="stat-number">12</div><div class="stat-note">Konten berhasil diposting</div></article>
    <article class="card"><div class="stat-top"><span class="muted small">Terjadwal</span><span class="stat-icon tint-yellow">◷</span></div><div class="stat-number">3</div><div class="stat-note">Menunggu waktu publikasi</div></article>
    <article class="card"><div class="stat-top"><span class="muted small">Perlu Perhatian</span><span class="stat-icon tint-purple">!</span></div><div class="stat-number">1</div><div class="stat-note">Postingan perlu pemeriksaan</div></article>
</div>

<div class="grid split">
    <article class="card">
        <div class="card-head"><div><h2>Akun Media Sosial</h2><p class="muted small">Status akun di workspace</p></div><a class="small" href="{{ route('akun') }}">Lihat semua →</a></div>
        <div class="platform-list">
            <div class="platform"><span class="platform-logo ig">IG</span><div class="platform-info"><strong>Instagram</strong><small>@kantor.official</small></div><span class="tag tag-green">Terhubung</span></div>
            <div class="platform"><span class="platform-logo fb">f</span><div class="platform-info"><strong>Facebook Page</strong><small>Kantor Official</small></div><span class="tag tag-green">Terhubung</span></div>
            <div class="platform"><span class="platform-logo tt">♪</span><div class="platform-info"><strong>TikTok</strong><small>Belum terhubung</small></div><span class="tag tag-yellow">Belum aktif</span></div>
            <div class="platform"><span class="platform-logo yt">▶</span><div class="platform-info"><strong>YouTube</strong><small>Belum terhubung</small></div><span class="tag tag-yellow">Belum aktif</span></div>
        </div>
    </article>
    <article class="card">
        <div class="card-head"><div><h2>Aktivitas Terbaru</h2><p class="muted small">Aktivitas workspace</p></div><span class="tag tag-blue">Hari ini</span></div>
        <div class="activity">
            <div class="activity-item"><span class="activity-dot"></span><div><strong>Postingan promo berhasil dibuat</strong><small>Instagram dan Facebook · 09.20</small></div></div>
            <div class="activity-item"><span class="activity-dot dot-yellow"></span><div><strong>Konten baru masuk jadwal</strong><small>Instagram · 08.45</small></div></div>
            <div class="activity-item"><span class="activity-dot dot-red"></span><div><strong>Postingan perlu diperiksa</strong><small>TikTok · Kemarin</small></div></div>
        </div>
    </article>
</div>

<article class="card">
    <div class="card-head"><div><h2>Postingan Terbaru</h2><p class="muted small">Daftar konten terakhir di workspace</p></div><a class="small" href="{{ route('riwayat') }}">Lihat riwayat →</a></div>
    <div class="table-wrap"><table><thead><tr><th>Nama Postingan</th><th>Platform</th><th>Tanggal</th><th>Status</th></tr></thead><tbody>
        <tr><td><strong>Promo akhir bulan</strong><br><span class="muted small">Foto carousel</span></td><td>Instagram, Facebook</td><td>15 Okt 2026</td><td><span class="tag tag-green">Berhasil</span></td></tr>
        <tr><td><strong>Highlight acara kantor</strong><br><span class="muted small">Video</span></td><td>Instagram, TikTok, YouTube</td><td>14 Okt 2026</td><td><span class="tag tag-red">Sebagian gagal</span></td></tr>
        <tr><td><strong>Informasi kelas baru</strong><br><span class="muted small">Foto</span></td><td>Instagram, Facebook</td><td>16 Okt 2026</td><td><span class="tag tag-yellow">Terjadwal</span></td></tr>
    </tbody></table></div>
</article>
@endsection
