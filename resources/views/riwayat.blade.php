@extends('layouts.app')
@section('title', 'Riwayat Postingan')
@section('breadcrumb', 'Riwayat Postingan')
@section('content')
<div class="page-head"><div><div class="eyebrow">Log publikasi</div><h1>Riwayat Postingan</h1><p class="muted">Rekap status konten yang sudah diproses di workspace.</p></div><button class="btn btn-light">⇩ Ekspor Riwayat</button></div>
<article class="card">
    <div class="card-head"><div><h2>Semua Postingan</h2><p class="muted small">Contoh data untuk tampilan antarmuka</p></div><span class="tag tag-blue">12 Konten</span></div>
    <div class="table-wrap"><table><thead><tr><th>Nama Postingan</th><th>Platform</th><th>Tanggal</th><th>Status</th><th>Catatan</th></tr></thead><tbody>
        <tr><td><strong>Promo akhir bulan</strong><br><span class="muted small">Carousel</span></td><td>Instagram, Facebook</td><td>15 Okt 2026</td><td><span class="tag tag-green">Berhasil</span></td><td class="muted">Publikasi selesai</td></tr>
        <tr><td><strong>Highlight acara kantor</strong><br><span class="muted small">Video</span></td><td>Instagram, TikTok, YouTube</td><td>14 Okt 2026</td><td><span class="tag tag-red">Sebagian gagal</span></td><td class="muted">Perlu pemeriksaan akun TikTok</td></tr>
        <tr><td><strong>Informasi kelas baru</strong><br><span class="muted small">Foto</span></td><td>Instagram, Facebook</td><td>16 Okt 2026</td><td><span class="tag tag-yellow">Terjadwal</span></td><td class="muted">Menunggu jadwal</td></tr>
        <tr><td><strong>Perkenalan layanan</strong><br><span class="muted small">Foto</span></td><td>Facebook</td><td>12 Okt 2026</td><td><span class="tag tag-green">Berhasil</span></td><td class="muted">Publikasi selesai</td></tr>
    </tbody></table></div>
</article>
@endsection
