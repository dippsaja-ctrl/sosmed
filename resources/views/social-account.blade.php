<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akun Media Sosial</title>
    <style>
        :root { --bg:#f6f7f9; --card:#fff; --text:#1d2430; --muted:#667085; --line:#e4e7ec; --accent:#2457d6; --ok:#12805c; --off:#b42318; }
        * { box-sizing: border-box; }
        body { margin:0; font-family: system-ui, -apple-system, "Segoe UI", sans-serif; background:var(--bg); color:var(--text); }
        .wrap { max-width: 960px; margin: 0 auto; padding: 32px 16px; }
        header { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:24px; }
        h1 { font-size:1.5rem; margin:0; }
        .btn { background:var(--accent); color:#fff; text-decoration:none; padding:10px 16px; border-radius:8px; font-weight:600; font-size:.9rem; }
        .btn:focus-visible, a:focus-visible { outline:3px solid #9bb7ff; outline-offset:2px; }
        .panel { background:var(--card); border:1px solid var(--line); border-radius:12px; overflow-x:auto; }
        table { width:100%; border-collapse:collapse; min-width:560px; }
        th, td { text-align:left; padding:14px 16px; border-bottom:1px solid var(--line); font-size:.95rem; }
        th { color:var(--muted); font-weight:600; font-size:.85rem; }
        tr:last-child td { border-bottom:0; }
        .status { font-weight:600; }
        .status.aktif { color:var(--ok); }
        .status.nonaktif { color:var(--off); }
        .empty { padding:48px 16px; text-align:center; color:var(--muted); }
    </style>
</head>
<body>
<div class="wrap">
    <header>
        <h1>Akun Media Sosial</h1>
        <a class="btn" href="{{ Route::has('social-accounts.create') ? route('social-accounts.create') : '#' }}">Tambah akun</a>
    </header>

    <div class="panel">
        @if (!empty($accounts) && count($accounts))
            <table>
                <thead>
                    <tr>
                        <th>Platform</th>
                        <th>Username</th>
                        <th>Pengikut</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                        <tr>
                            <td>{{ $account->platform }}</td>
                            <td>
                                @if (!empty($account->url))
                                    <a href="{{ $account->url }}" target="_blank" rel="noopener">{{ $account->username }}</a>
                                @else
                                    {{ $account->username }}
                                @endif
                            </td>
                            <td>{{ number_format($account->followers ?? 0, 0, ',', '.') }}</td>
                            <td>
                                @php $active = $account->is_active ?? true; @endphp
                                <span class="status {{ $active ? 'aktif' : 'nonaktif' }}">{{ $active ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">Belum ada akun yang terhubung. Pilih "Tambah akun" untuk memulai.</div>
        @endif
    </div>
</div>
</body>
</html>