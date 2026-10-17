<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pos Bersama') - Pos Bersama</title>
    <link rel="stylesheet" href="{{ asset('css/posbersama.css') }}">
</head>
<body>
@php
    $uid = auth()->id();
    $navScheduled = \App\Models\Post::where('user_id', $uid)->where('state', 'scheduled')->count();
    $navFailed = \App\Models\Post::where('user_id', $uid)->where('state', 'done')
        ->whereHas('targets', fn ($q) => $q->where('status', 'failed'))->count();
    $notifs = auth()->user()->notifications()->limit(8)->get();
    $unread = auth()->user()->unreadNotifications()->count();
@endphp
<div class="shell">
    <aside class="side">
        <div class="brand"><span class="mark">PB</span> Pos Bersama</div>
        <nav class="nav" aria-label="Menu utama">
            <a href="{{ route('dashboard') }}" @class(['on' => request()->routeIs('dashboard')])>Dashboard</a>
            <a href="{{ route('accounts.index') }}" @class(['on' => request()->routeIs('accounts.*')])>Akun Sosmed</a>
            <a href="{{ route('posts.create') }}" @class(['on' => request()->routeIs('posts.*')])>Buat posting</a>
            <a href="{{ route('schedule.index') }}" @class(['on' => request()->routeIs('schedule.*')])>Terjadwal @if($navScheduled)<span class="cnt">{{ $navScheduled }}</span>@endif</a>
            <a href="{{ route('history.index') }}" @class(['on' => request()->routeIs('history.*')])>Riwayat @if($navFailed)<span class="cnt bad">{{ $navFailed }}</span>@endif</a>
        </nav>
    </aside>
    <div class="main">
        <header class="top">
            <h1>@yield('title')</h1>
            <details class="bell">
                <summary>Notifikasi @if($unread)<span class="cnt bad">{{ $unread }}</span>@endif</summary>
                <div class="bell-pop">
                    @forelse($notifs as $n)
                        <div class="attn {{ ($n->data['type'] ?? '') === 'ok' ? 'ok' : 'bad' }}">
                            <span>{{ $n->data['text'] ?? '' }}<br><small class="muted">{{ $n->created_at->diffForHumans() }}</small></span>
                        </div>
                    @empty
                        <p class="muted">Belum ada notifikasi.</p>
                    @endforelse
                    @if($unread)
                        <form method="POST" action="{{ route('notifications.read') }}">@csrf
                            <button class="btn sm" type="submit">Tandai sudah dibaca</button>
                        </form>
                    @endif
                </div>
            </details>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="btn sm" type="submit">Keluar ({{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }})</button>
            </form>
        </header>
        <main class="page">
            @if(session('ok'))<div class="flash ok" role="status">{{ session('ok') }}</div>@endif
            @if($errors->any())
                <div class="flash bad" role="alert"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
