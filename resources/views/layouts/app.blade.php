
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Posting Sosmed') - Posting Sosmed</title>

    <link rel="stylesheet" href="{{ asset('css/posbersama.css') }}">

    <style>
        /* LOGO POSTING SOSMED */
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 700;
            white-space: nowrap;
        }

        .social-upload-logo {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 43px;
            height: 43px;
            flex-shrink: 0;
        }

        .social-upload-logo .ring {
            position: absolute;
            inset: 5px;
            border: 3px solid transparent;
            border-radius: 50%;
            background:
                linear-gradient(#17243b, #17243b) padding-box,
                linear-gradient(
                    135deg,
                    #d629ff,
                    #087cff,
                    #ff1744
                ) border-box;
        }

        .social-upload-logo .social {
            position: absolute;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            z-index: 2;
        }

        .social-upload-logo .social svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        .social-upload-logo .ig {
            top: 0;
            left: 0;
            background: #E4405F;
        }

        .social-upload-logo .tt {
            top: 0;
            right: 0;
            background: #111111;
        }

        .social-upload-logo .fb {
            bottom: 0;
            left: 0;
            background: #1877F2;
        }

        .social-upload-logo .yt {
            bottom: 0;
            right: 0;
            background: #FF0000;
        }

        .social-upload-logo .upload {
            position: relative;
            z-index: 3;
            width: 27px;
            height: 27px;
        }
    </style>
</head>

<body>

@php
    $uid = auth()->id();

    $navScheduled = \App\Models\Post::where('user_id', $uid)
        ->where('state', 'scheduled')
        ->count();

    $navFailed = \App\Models\Post::where('user_id', $uid)
        ->where('state', 'done')
        ->whereHas('targets', fn ($q) => $q->where('status', 'failed'))
        ->count();

    $notifs = auth()->user()->notifications()->limit(8)->get();
    $unread = auth()->user()->unreadNotifications()->count();
@endphp

<div class="shell">

    <aside class="side">

        <!-- LOGO BARU PENGGANTI PB -->
        <div class="brand">

            <span class="social-upload-logo">

                <span class="ring"></span>

                <!-- Instagram -->
                <span class="social ig">
                    <svg viewBox="0 0 24 24" fill="none">
                        <rect x="3" y="3" width="18" height="18"
                            rx="5" stroke="white" stroke-width="2.5"/>
                        <circle cx="12" cy="12" r="4"
                            stroke="white" stroke-width="2.5"/>
                        <circle cx="17.5" cy="6.5" r="1.3"
                            fill="white"/>
                    </svg>
                </span>

                <!-- TikTok -->
                <span class="social tt">
                    <svg viewBox="0 0 24 24">
                        <path d="M14 3v11a4.5 4.5 0 1 1-4-4.47"
                            fill="none" stroke="#25F4EE"
                            stroke-width="4" stroke-linecap="round"/>
                        <path d="M14 3c1 3 3 4.5 6 5"
                            fill="none" stroke="white"
                            stroke-width="3" stroke-linecap="round"/>
                        <path d="M14 3v11a4.5 4.5 0 1 1-4-4.47"
                            fill="none" stroke="#FE2C55"
                            stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>

                <!-- Facebook -->
                <span class="social fb">
                    <svg viewBox="0 0 24 24">
                        <path d="M14 21v-8h3l.5-4H14V7
                            c0-1.2.4-2 2-2h2V1.5
                            C17.2 1.3 16.2 1.2 15 1.2
                            c-3.5 0-5.5 2.1-5.5 5.5V9H6v4h3.5v8Z"
                            fill="white"/>
                    </svg>
                </span>

                <!-- YouTube -->
                <span class="social yt">
                    <svg viewBox="0 0 24 24">
                        <rect x="2" y="5" width="20" height="14"
                            rx="4" fill="white"/>
                        <path d="M10 8.5 16 12l-6 3.5Z"
                            fill="#FF0000"/>
                    </svg>
                </span>

                <!-- Upload video -->
                <svg class="upload" viewBox="0 0 64 64" fill="none">
                    <rect x="5" y="24" width="54" height="35"
                        rx="9" fill="white"/>
                    <path d="M27 32 44 41.5 27 51Z"
                        fill="#17243b"/>
                    <path d="M32 27V4M22 14 32 4 42 14"
                        stroke="white" stroke-width="6"
                        stroke-linecap="round"
                        stroke-linejoin="round"/>
                    <path d="M2 33H12M1 42H12M3 51H12"
                        stroke="white" stroke-width="4"
                        stroke-linecap="round"/>
                </svg>

            </span>

            <span>Posting Sosmed</span>

        </div>

        <!-- MENU SIDEBAR -->
        <nav class="nav" aria-label="Menu utama">

            <a href="{{ route('dashboard') }}"
                @class(['on' => request()->routeIs('dashboard')])>
                Dashboard
            </a>

            <a href="{{ route('accounts.index') }}"
                @class(['on' => request()->routeIs('accounts.*')])>
                Akun Sosmed
            </a>

            <a href="{{ route('posts.create') }}"
                @class(['on' => request()->routeIs('posts.*')])>
                Buat posting
            </a>

            <a href="{{ route('schedule.index') }}"
                @class(['on' => request()->routeIs('schedule.*')])>
                Terjadwal

                @if($navScheduled)
                    <span class="cnt">{{ $navScheduled }}</span>
                @endif
            </a>

            <a href="{{ route('history.index') }}"
                @class(['on' => request()->routeIs('history.*')])>
                Riwayat

                @if($navFailed)
                    <span class="cnt bad">{{ $navFailed }}</span>
                @endif
            </a>

        </nav>
    </aside>

    <div class="main">

        <header class="top">

            <h1>@yield('title')</h1>

            <details class="bell">

                <summary>
                    Notifikasi

                    @if($unread)
                        <span class="cnt bad">{{ $unread }}</span>
                    @endif
                </summary>

                <div class="bell-pop">

                    @forelse($notifs as $n)

                        <div class="attn {{ ($n->data['type'] ?? '') === 'ok' ? 'ok' : 'bad' }}">

                            <span>
                                {{ $n->data['text'] ?? '' }}
                                <br>

                                <small class="muted">
                                    {{ $n->created_at->diffForHumans() }}
                                </small>
                            </span>

                        </div>

                    @empty

                        <p class="muted">Belum ada notifikasi.</p>

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

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button class="btn sm" type="submit">
                    Keluar ({{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }})
                </button>
            </form>

        </header>

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

@stack('scripts')

</body>
</html>
