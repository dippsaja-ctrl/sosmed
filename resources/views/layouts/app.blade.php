<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pos Bersama') - Pos Bersama</title>
    <link rel="stylesheet" href="{{ asset('css/pos-bersama.css') }}">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="brand">
            <span class="brand-mark">PB</span>
            <span><strong>Pos Bersama</strong><small>Social Media Workspace</small></span>
        </a>

        <div>
            <div class="nav-label">MENU UTAMA</div>
            <nav class="nav">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="nav-icon">⌂</span>Dashboard</a>
                <a href="{{ route('akun') }}" class="{{ request()->routeIs('akun') ? 'active' : '' }}"><span class="nav-icon">↗</span>Akun Media Sosial</a>
                <a href="{{ route('posting') }}" class="{{ request()->routeIs('posting') ? 'active' : '' }}"><span class="nav-icon">✎</span>Buat Postingan</a>
                <a href="{{ route('terjadwal') }}" class="{{ request()->routeIs('terjadwal') ? 'active' : '' }}"><span class="nav-icon">◷</span>Postingan Terjadwal</a>
                <a href="{{ route('riwayat') }}" class="{{ request()->routeIs('riwayat') ? 'active' : '' }}"><span class="nav-icon">↺</span>Riwayat Postingan</a>
            </nav>
        </div>

        <div class="side-bottom">Pos Bersama<br>Workspace posting multi-sosmed</div>
    </aside>

    <main class="main">
        <header class="topbar">
            <div class="crumb">Workspace / <strong>@yield('breadcrumb', 'Dashboard')</strong></div>
            <div class="top-right">
                <span class="icon-button">♧</span>
                <div class="profile">
                    <span class="avatar">PB</span>
                    <span><strong>Pengguna Demo</strong><small>Administrator</small></span>
                </div>
            </div>
        </header>

        <div class="content">
            @yield('content')
            <p class="footer-note">Pos Bersama · Tampilan frontend Laravel</p>
        </div>
    </main>
</div>
</body>
</html>
