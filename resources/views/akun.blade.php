@extends('layouts.app')
@section('title', 'Akun Media Sosial')
@section('breadcrumb', 'Akun Media Sosial')
@section('content')
<div class="page-head"><div><div class="eyebrow">Kelola koneksi</div><h1>Akun Media Sosial</h1><p class="muted">Ringkasan akun media sosial untuk publikasi konten.</p></div><span class="tag tag-blue">4 Platform</span></div>
<div class="grid account-grid">
    <article class="card account-card"><div class="platform"><span class="platform-logo ig">IG</span><div class="platform-info"><strong>Instagram</strong><small>Foto, Reels, carousel</small></div></div><span class="tag tag-green">Terhubung</span><p class="muted small">Akun: @kantor.official</p><button class="btn btn-light">Kelola Akun</button></article>
    <article class="card account-card"><div class="platform"><span class="platform-logo fb">f</span><div class="platform-info"><strong>Facebook Page</strong><small>Foto, video, multi-foto</small></div></div><span class="tag tag-green">Terhubung</span><p class="muted small">Halaman: Kantor Official</p><button class="btn btn-light">Kelola Akun</button></article>
    <article class="card account-card"><div class="platform"><span class="platform-logo tt">♪</span><div class="platform-info"><strong>TikTok</strong><small>Video dan foto carousel</small></div></div><span class="tag tag-yellow">Belum terhubung</span><p class="muted small">Hubungkan akun untuk menyiapkan publikasi.</p><button class="btn btn-primary">Hubungkan Akun</button></article>
    <article class="card account-card"><div class="platform"><span class="platform-logo yt">▶</span><div class="platform-info"><strong>YouTube</strong><small>Video</small></div></div><span class="tag tag-yellow">Belum terhubung</span><p class="muted small">Hubungkan channel untuk menyiapkan unggahan.</p><button class="btn btn-primary">Hubungkan Akun</button></article>
</div>
@endsection
