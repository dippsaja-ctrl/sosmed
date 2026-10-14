@extends('layouts.app')
@section('title', 'Postingan Terjadwal')
@section('breadcrumb', 'Postingan Terjadwal')
@section('content')
<div class="page-head"><div><div class="eyebrow">Kalender publikasi</div><h1>Postingan Terjadwal</h1><p class="muted">Daftar konten yang direncanakan untuk dipublikasikan.</p></div><span class="tag tag-yellow">3 Menunggu</span></div>
<article class="card">
    <div class="card-head"><div><h2>Jadwal Mendatang</h2><p class="muted small">Daftar jadwal posting</p></div><button class="btn btn-light">▦ Tampilan Kalender</button></div>
    <div class="table-wrap"><table><thead><tr><th>Konten</th><th>Platform</th><th>Waktu Publikasi</th><th>Status</th></tr></thead><tbody>
        <tr><td><strong>Informasi kelas baru</strong><br><span class="muted small">Foto · Caption informasi</span></td><td>Instagram, Facebook</td><td>16 Okt 2026 · 09.00</td><td><span class="tag tag-yellow">Terjadwal</span></td></tr>
        <tr><td><strong>Tips mingguan</strong><br><span class="muted small">Carousel · 4 media</span></td><td>Instagram</td><td>17 Okt 2026 · 13.00</td><td><span class="tag tag-yellow">Terjadwal</span></td></tr>
        <tr><td><strong>Video pengenalan</strong><br><span class="muted small">Video pendek</span></td><td>YouTube</td><td>18 Okt 2026 · 10.30</td><td><span class="tag tag-yellow">Terjadwal</span></td></tr>
    </tbody></table></div>
</article>
@endsection
