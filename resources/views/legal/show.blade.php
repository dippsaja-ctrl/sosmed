<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $page === 'privacy' ? 'Kebijakan Privasi' : 'Syarat Layanan' }} - Posting Sosmed</title>
<link rel="stylesheet" href="{{ asset('css/posbersama.css') }}"></head>
<body><div class="legal card">
@if($page === 'privacy')
    <h2>Kebijakan Privasi</h2>
    <p>Aplikasi Posting Sosmed dipakai internal kantor untuk memposting konten ke akun sosial media milik kantor.</p>
    <p>Kami menyimpan token akses (terenkripsi) hanya untuk memposting atas nama akun yang Anda hubungkan. Kami tidak membaca pesan pribadi dan tidak membagikan data ke pihak lain. Anda dapat memutus koneksi kapan saja di menu Akun Sosmed.</p>
@else
    <h2>Syarat Layanan</h2>
    <p>Aplikasi ini hanya untuk staf kantor yang berwenang. Pengguna bertanggung jawab atas isi konten yang diposting dan wajib mematuhi aturan tiap platform.</p>
    <p>Akses dapat dicabut sewaktu-waktu. Layanan disediakan apa adanya selama masa PKL.</p>
@endif
<p class="hint">Teks contoh singkat. Ganti dengan teks resmi dari kantor sebelum didaftarkan ke platform.</p>
<p><a href="{{ url('/') }}">Kembali ke aplikasi</a></p>
</div></body></html>
