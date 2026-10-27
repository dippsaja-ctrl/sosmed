
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Posting Sosmed') - Posting Sosmed</title>
    <link rel="stylesheet" href="{{ asset('css/posbersama.css') }}">

    <style>
        /* ================= SIDEBAR ================= */
        #appShell {
            display: grid;
            grid-template-columns: 250px minmax(0, 1fr);
            width: 100%;
            min-height: 100vh;
            transition: grid-template-columns .3s ease;
        }

        #appShell > .side {
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            width: 250px;
            min-width: 0;
            height: 100vh;
            padding: 0;
            overflow-x: hidden;
            overflow-y: auto;
            box-sizing: border-box;
            z-index: 20;
        }

        .side-top {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 82px;
            padding: 14px;
            box-sizing: border-box;
        }

        .hamburger-toggle,
        #sidebarReopen {
            display: flex;
            flex: 0 0 40px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 5px;
            width: 40px;
            height: 40px;
            padding: 0;
            border: 1px solid rgba(255,255,255,.22);
            border-radius: 10px;
            background: transparent;
            cursor: pointer;
            transition: background .2s, transform .2s;
        }

        .hamburger-toggle:hover {
            background: rgba(255,255,255,.12);
        }

        .hamburger-toggle span,
        #sidebarReopen span {
            display: block;
            width: 19px;
            height: 2px;
            border-radius: 5px;
            background: #fff;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
            margin: 0;
            padding: 0;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            white-space: nowrap;
        }

        .brand-bird {
            display: block;
            flex: 0 0 48px;
            width: 48px;
            height: 48px;
            object-fit: contain;
            object-position: center;
            filter: drop-shadow(0 2px 5px rgba(0,0,0,.25));
        }

        .brand span {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #appShell > .side > .nav {
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            align-items: stretch;
            gap: 5px;
            width: 100%;
            margin: 0;
            padding: 6px 12px 14px;
            box-sizing: border-box;
        }

        #appShell > .side > .nav > a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            width: 100%;
            min-height: 43px;
            margin: 0;
            padding: 11px 12px;
            border-radius: 10px;
            box-sizing: border-box;
            line-height: 1.4;
            text-decoration: none;
            transition: background .2s, padding .2s;
        }

        #appShell > .side > .nav > a:hover {
            background: rgba(255,255,255,.09);
        }

        #appShell > .side > .nav > a.on {
            padding-left: 9px;
            border-left: 3px solid #ffb000;
            background: #1d3d5e;
        }

        .side-bottom {
            flex: 0 0 auto;
            margin-top: auto;
            padding: 14px 12px 18px;
            border-top: 1px solid rgba(255,255,255,.12);
        }

        .side-bottom form {
            width: 100%;
            margin: 0;
        }

        .side-logout {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            min-height: 48px;
            padding: 12px 14px;
            color: #fff;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 10px;
            font: inherit;
            text-align: left;
            cursor: pointer;
            transition: background .2s;
        }

        .side-logout:hover {
            background: rgba(255,255,255,.15);
        }

        .side-logout svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        /* ================= AREA KONTEN ================= */
        #appShell > .main {
            display: flex;
            flex-direction: column;
            width: 100%;
            min-width: 0;
            max-width: none;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        #appShell > .main > .top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        .top h1 {
            min-width: 0;
            margin: 0;
            overflow-wrap: anywhere;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        #appShell > .main > .page {
            width: 100%;
            min-width: 0;
            max-width: none;
            box-sizing: border-box;
        }

        /* ================= SIDEBAR DITUTUP ================= */
        #appShell.sidebar-collapsed {
            grid-template-columns: minmax(0, 1fr);
        }

        #appShell.sidebar-collapsed > .side {
            display: none;
        }

        #appShell.sidebar-collapsed > .main {
            grid-column: 1 / -1;
            width: 100%;
            min-width: 0;
            max-width: none;
            margin: 0;
        }

        #appShell.sidebar-collapsed > .main > .top,
        #appShell.sidebar-collapsed > .main > .page {
            width: 100%;
            max-width: none;
            margin-left: 0;
            margin-right: 0;
        }

        #sidebarReopen {
            display: none;
            position: fixed;
            top: 14px;
            left: 14px;
            z-index: 1100;
            background: #102d55;
            border-color: #334a63;
            box-shadow: 0 4px 14px rgba(0,0,0,.2);
            animation: sidebarButtonIn .22s ease both;
        }

        #appShell.sidebar-collapsed ~ #sidebarReopen {
            display: flex;
        }

        #appShell.sidebar-collapsed > .main > .top {
            padding-left: 68px;
        }

        @keyframes sidebarButtonIn {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* ================= TOMBOL DAN DROPDOWN ================= */
        .dropdown {
            position: relative;
        }

        .icon-button {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            min-width: 42px;
            padding: 0;
            color: #123052;
            background: #fff;
            border: 1px solid #d5dfeb;
            border-radius: 12px;
            cursor: pointer;
            list-style: none;
            box-sizing: border-box;
        }

        .icon-button::-webkit-details-marker {
            display: none;
        }

        .icon-button svg {
            width: 22px;
            height: 22px;
        }

        .icon-button:hover {
            background: #f0f5fb;
        }

        .icon-button .cnt,
        .nav .cnt {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 18px;
            min-height: 18px;
            padding: 1px 5px;
            color: #fff;
            background: #e53945;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            box-sizing: border-box;
        }

        .icon-button .cnt {
            position: absolute;
            top: -6px;
            right: -6px;
        }

        .dropdown-pop,
        .bell-pop {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            z-index: 1000;
            color: #17314f;
            background: #fff;
            border: 1px solid #dce5ef;
            border-radius: 14px;
            box-shadow: 0 12px 32px rgba(13,35,62,.18);
        }

        .dropdown-pop {
            width: 270px;
            max-width: calc(100vw - 24px);
            padding: 10px;
            box-sizing: border-box;
        }

        .dropdown-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            margin-bottom: 6px;
            border-bottom: 1px solid #e8edf4;
        }

        .avatar-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            min-width: 48px;
            flex: 0 0 48px;
            overflow: hidden;
            color: #173b65;
            background: #e5effc;
            border-radius: 50%;
        }

        .avatar-circle svg {
            width: 24px;
            height: 24px;
        }

        .user-info {
            min-width: 0;
        }

        .user-info strong,
        .user-info small {
            display: block;
            overflow-wrap: anywhere;
        }

        .user-info strong {
            font-size: 14px;
        }

        .user-info small {
            margin-top: 4px;
            color: #74859a;
            font-size: 12px;
        }

        .dropdown-link,
        .dropdown-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 11px 10px;
            color: #233c59;
            background: transparent;
            border: 0;
            border-radius: 8px;
            font: inherit;
            font-size: 13px;
            text-align: left;
            text-decoration: none;
            cursor: pointer;
            box-sizing: border-box;
        }

        .dropdown-link:hover,
        .dropdown-logout:hover {
            background: #f0f5fb;
            text-decoration: none;
        }

        .dropdown-link svg,
        .dropdown-logout svg {
            width: 19px;
            height: 19px;
            flex-shrink: 0;
        }

        .dropdown-divider {
            height: 1px;
            margin: 6px 0;
            background: #e8edf4;
        }

        .dropdown-logout {
            color: #d23b43;
        }

        /* ================= NOTIFIKASI ================= */
        .bell-pop {
            width: 330px;
            max-width: calc(100vw - 24px);
            max-height: 400px;
            overflow-y: auto;
            padding: 14px;
            box-sizing: border-box;
        }

        .bell-title {
            padding: 4px 2px 12px;
            margin-bottom: 10px;
            border-bottom: 1px solid #e8edf4;
            font-size: 15px;
            font-weight: 700;
        }

        .notification-empty {
            padding: 12px 2px;
            color: #75869b;
            font-size: 13px;
        }

        .bell-pop .attn {
            margin-bottom: 8px;
        }

        /* ================= FOTO PROFIL ================= */
        .profile-trigger-avatar,
        .profile-avatar-image {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile-dropdown > summary.profile-trigger {
            overflow: hidden;
            border-radius: 50%;
        }

        .profile-trigger-initial,
        .profile-avatar-initial {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: #123052;
            background: #e5effc;
            border-radius: 50%;
            font-size: 18px;
            font-weight: 700;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            #appShell {
                display: block;
                width: 100%;
            }

            #appShell > .side {
                position: fixed;
                top: 0;
                left: 0;
                width: 250px;
                max-width: 85vw;
                height: 100vh;
                z-index: 1050;
                background: #10243a;
                box-shadow: 8px 0 24px rgba(0,0,0,.2);
                animation: sidebarSlideIn .25s ease both;
            }

            #appShell.sidebar-collapsed > .side {
                display: none;
            }

            #appShell > .main {
                width: 100%;
                min-width: 0;
            }

            #appShell.sidebar-collapsed > .main > .top {
                padding-left: 68px;
            }

            .side-top {
                min-height: 78px;
            }

            .brand-bird {
                width: 44px;
                height: 44px;
                flex-basis: 44px;
            }

            .top-actions {
                gap: 7px;
            }

            .icon-button {
                width: 38px;
                height: 38px;
                min-width: 38px;
            }
        }

        @keyframes sidebarSlideIn {
            from {
                transform: translateX(-100%);
                opacity: .75;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 420px) {
            #appShell > .main > .top {
                gap: 8px;
            }

            #appShell > .main > .top h1 {
                font-size: 20px;
            }

            .top-actions {
                gap: 5px;
            }

            .dropdown-pop {
                right: -4px;
            }

            .bell-pop {
                right: -48px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            #appShell,
            .hamburger-toggle,
            #sidebarReopen,
            #appShell > .side > .nav > a {
                transition: none !important;
                animation: none !important;
            }
        }
    </style>
</head>

<body>
@php
    $uid = auth()->id();
    $currentUser = auth()->user();

    $navScheduled = \App\Models\Post::where('user_id', $uid)
        ->where('state', 'scheduled')
        ->count();

    $navFailed = \App\Models\Post::where('user_id', $uid)
        ->where('state', 'done')
        ->whereHas('targets', fn ($q) => $q->where('status', 'failed'))
        ->count();

    $notifs = $currentUser->notifications()->limit(8)->get();
    $unread = $currentUser->unreadNotifications()->count();
@endphp

<div class="shell" id="appShell">
    {{-- SIDEBAR --}}
    <aside class="side">
        <div class="side-top">
            <button
                type="button"
                class="hamburger-toggle"
                id="sidebarHamburger"
                aria-label="Tutup sidebar"
                aria-expanded="true"
                title="Tutup sidebar"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="brand">
                <img
                    class="brand-bird"
                    src="{{ asset('images/logo.png') }}"
                    alt="Logo Posting Sosmed"
                >
                <span>Posting Sosmed</span>
            </div>
        </div>

        {{-- MENU UTAMA --}}
        <nav class="nav" aria-label="Menu utama">
            <a href="{{ route('dashboard') }}"
               @class(['on' => request()->routeIs('dashboard')])>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('accounts.index') }}"
               @class(['on' => request()->routeIs('accounts.*')])>
                <span>Akun Sosmed</span>
            </a>

            <a href="{{ route('posts.create') }}"
               @class(['on' => request()->routeIs('posts.*')])>
                <span>Buat posting</span>
            </a>

            <a href="{{ route('schedule.index') }}"
               @class(['on' => request()->routeIs('schedule.*')])>
                <span>Terjadwal</span>
                @if($navScheduled)
                    <span class="cnt">{{ $navScheduled }}</span>
                @endif
            </a>

            <a href="{{ route('history.index') }}"
               @class(['on' => request()->routeIs('history.*')])>
                <span>Riwayat</span>
                @if($navFailed)
                    <span class="cnt bad">{{ $navFailed }}</span>
                @endif
            </a>
        </nav>

        {{-- LOGOUT --}}
        <div class="side-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="side-logout">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M10 17l5-5-5-5"/>
                        <path d="M15 12H3"/>
                        <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- AREA KONTEN --}}
    <div class="main">
        <header class="top">
            <h1>@yield('title')</h1>

            <div class="top-actions">
                {{-- NOTIFIKASI --}}
                <details class="dropdown bell">
                    <summary class="icon-button"
                             aria-label="Notifikasi"
                             title="Notifikasi">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                            <path d="M10 21h4"/>
                        </svg>

                        @if($unread)
                            <span class="cnt">{{ $unread }}</span>
                        @endif
                    </summary>

                    <div class="bell-pop">
                        <div class="bell-title">Notifikasi</div>

                        @forelse($notifs as $n)
                            <div class="attn {{ ($n->data['type'] ?? '') === 'ok' ? 'ok' : 'bad' }}">
                                <span>
                                    {{ $n->data['text'] ?? 'Ada pembaruan notifikasi.' }}
                                    <br>
                                    <small class="muted">
                                        {{ $n->created_at->diffForHumans() }}
                                    </small>
                                </span>
                            </div>
                        @empty
                            <p class="notification-empty">Belum ada notifikasi.</p>
                        @endforelse

                        @if($unread)
                            <form method="POST" action="{{ route('notifications.read') }}">
                                @csrf
                                <button class="btn sm" type="submit">
                                    Tandai sudah dibaca
                                </button>
                            </form>
                        @endif
                    </div>
                </details>

                {{-- PROFIL --}}
                <details class="dropdown profile-dropdown">
                    <summary class="icon-button profile-trigger"
                             aria-label="Menu profil"
                             title="Profil">
                        @if($currentUser->profile_photo)
                            <img
                                src="{{ asset('storage/' . $currentUser->profile_photo) }}"
                                alt="Foto profil"
                                class="profile-trigger-avatar"
                            >
                        @else
                            <span class="profile-trigger-initial">
                                {{ mb_strtoupper(mb_substr(trim($currentUser->name ?? '?'), 0, 1)) }}
                            </span>
                        @endif
                    </summary>

                    <div class="dropdown-pop">
                        <div class="dropdown-heading">
                            <div class="avatar-circle">
                                @if($currentUser->profile_photo)
                                    <img
                                        src="{{ asset('storage/' . $currentUser->profile_photo) }}"
                                        alt="Foto profil"
                                        class="profile-avatar-image"
                                    >
                                @else
                                    <span class="profile-avatar-initial">
                                        {{ mb_strtoupper(mb_substr(trim($currentUser->name ?? '?'), 0, 1)) }}
                                    </span>
                                @endif
                            </div>

                            <div class="user-info">
                                <strong>{{ $currentUser->name }}</strong>
                                <small>{{ $currentUser->email }}</small>
                            </div>
                        </div>

                        @if(\Illuminate\Support\Facades\Route::has('profile.edit'))
                            <a href="{{ route('profile.edit') }}" class="dropdown-link">
                                <svg viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.8"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="4"/>
                                    <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                                </svg>
                                Profil
                            </a>
                        @endif

                        @if(\Illuminate\Support\Facades\Route::has('settings.index'))
                            <a href="{{ route('settings.index') }}" class="dropdown-link">
                                <svg viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.8"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M19.4 15a8 8 0 0 0 0-6l1.4-1.1-1.5-2.6-1.7.7a8 8 0 0 0-5.2-1.5L12 2.7H9l-.4 1.8a8 8 0 0 0-2.3 1.3l-1.7-.7-1.5 2.6L4.5 9a8 8 0 0 0 0 6l-1.4 1.1 1.5 2.6 1.7-.7a8 8 0 0 0 5.2 1.5l.4 1.8h3l.4-1.8a8 8 0 0 0 2.3-1.3l1.7.7 1.5-2.6z"/>
                                </svg>
                                Pengaturan
                            </a>
                        @endif

                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-logout">
                                <svg viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.8"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 17l5-5-5-5"/>
                                    <path d="M15 12H3"/>
                                    <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </details>
            </div>
        </header>

        {{-- PESAN SUKSES, ERROR, DAN ISI HALAMAN --}}
        <main class="page">
            @if(session('ok'))
                <div class="flash ok" role="status">
                    {{ session('ok') }}
                </div>
            @endif

            @if($errors->any())
                <div class="flash bad" role="alert">
                    <ul>
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

{{-- TOMBOL UNTUK MEMBUKA KEMBALI SIDEBAR --}}
<button type="button"
        id="sidebarReopen"
        aria-label="Buka sidebar"
        aria-expanded="false"
        title="Buka sidebar">
    <span></span>
    <span></span>
    <span></span>
</button>

<script>
(function () {
    'use strict';

    const shell = document.getElementById('appShell');
    const closeButton = document.getElementById('sidebarHamburger');
    const openButton = document.getElementById('sidebarReopen');
    const storageKey = 'postingSosmedSidebarCollapsed';

    if (!shell || !closeButton || !openButton) return;

    function setCollapsed(collapsed, saveState = true) {
        shell.classList.toggle('sidebar-collapsed', collapsed);

        closeButton.setAttribute('aria-expanded', String(!collapsed));
        closeButton.setAttribute('aria-label', 'Tutup sidebar');
        closeButton.setAttribute('title', 'Tutup sidebar');

        openButton.setAttribute('aria-expanded', String(collapsed));
        openButton.setAttribute('aria-label', 'Buka sidebar');
        openButton.setAttribute('title', 'Buka sidebar');

        if (saveState) {
            try {
                localStorage.setItem(storageKey, collapsed ? '1' : '0');
            } catch (error) {
                // Sidebar tetap berfungsi jika penyimpanan browser tidak tersedia.
            }
        }
    }

    let initiallyCollapsed = false;

    try {
        initiallyCollapsed = localStorage.getItem(storageKey) === '1';
    } catch (error) {
        // Gunakan sidebar terbuka jika localStorage tidak tersedia.
    }

    setCollapsed(initiallyCollapsed, false);

    closeButton.addEventListener('click', function (event) {
        event.preventDefault();
        setCollapsed(true);
    });

    openButton.addEventListener('click', function (event) {
        event.preventDefault();
        setCollapsed(false);
    });

    // Tutup dropdown ketika pengguna mengeklik area di luar dropdown.
    document.addEventListener('click', function (event) {
        document.querySelectorAll('.dropdown[open]').forEach(function (dropdown) {
            if (!dropdown.contains(event.target)) {
                dropdown.removeAttribute('open');
            }
        });
    });

    // Tombol Escape menutup dropdown yang sedang terbuka.
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.dropdown[open]').forEach(function (dropdown) {
                dropdown.removeAttribute('open');
            });
        }
    });
})();
</script>

@stack('scripts')
</body>
</html>
