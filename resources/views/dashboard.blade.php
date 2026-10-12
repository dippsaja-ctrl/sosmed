<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <style>
        :root { --bg:#f6f7f9; --card:#fff; --text:#1d2430; --muted:#667085; --line:#e4e7ec; --accent:#2457d6; }
        * { box-sizing: border-box; }
        body { margin:0; font-family: system-ui, -apple-system, "Segoe UI", sans-serif; background:var(--bg); color:var(--text); }
        .wrap { max-width: 960px; margin: 0 auto; padding: 32px 16px; }
        h1 { font-size:1.5rem; margin:0 0 4px; }
        .sub { color:var(--muted); margin:0 0 24px; }
        .grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; }
        .stat { background:var(--card); border:1px solid var(--line); border-radius:12px; padding:20px; }
        .stat .label { color:var(--muted); font-size:.9rem; }
        .stat .value { font-size:2rem; font-weight:700; margin-top:6px; }
        .links { margin-top:24px; }
        .links a { color:var(--accent); font-weight:600; }
        .links a:focus-visible { outline:3px solid #9bb7ff; outline-offset:2px; }
    </style>
</head>
<body>
<div class="wrap">
    <h1>Dashboard</h1>
    <p class="sub">Ringkasan akun media sosial Anda.</p>

    <div class="grid">
        <div class="stat">
            <div class="label">Total akun</div>
            <div class="value">{{ number_format($totalAccounts ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <div class="label">Akun aktif</div>
            <div class="value">{{ number_format($activeAccounts ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <div class="label">Total pengikut</div>
            <div class="value">{{ number_format($totalFollowers ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <div class="label">Jumlah platform</div>
            <div class="value">{{ number_format($totalPlatforms ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>

    <p class="links">
        <a href="{{ Route::has('social-accounts.index') ? route('social-accounts.index') : '#' }}">Lihat semua akun</a>
    </p>
</div>
</body>
</html>