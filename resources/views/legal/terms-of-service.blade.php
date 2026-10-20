
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ketentuan Layanan - Pos Bersama</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 30px 16px;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: linear-gradient(120deg, #061020, #10294b);
            color: #303b52;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 32px;
            background: #f8faff;
            border-radius: 18px;
            box-shadow: 0 15px 45px #0002;
            line-height: 1.8;
        }
        h1 { color: #24283f; }
        h2 { margin-top: 25px; font-size: 18px; }
        p { font-size: 14px; }
        .back {
            display: inline-block;
            margin-top: 25px;
            color: #3265b7;
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .container { padding: 22px; }
        }
    </style>
</head>
<body>
    <main class="container">
        <h1>Ketentuan Layanan</h1>

        <p>
            Selamat datang di Pos Bersama. Ketentuan ini menjelaskan
            aturan dasar penggunaan layanan yang tersedia pada aplikasi.
        </p>

        <h2>1. Penggunaan Akun</h2>
        <p>
            Pengguna bertanggung jawab menjaga keamanan akun dan
            memberikan informasi yang benar ketika melakukan pendaftaran.
        </p>

        <h2>2. Penggunaan Aplikasi</h2>
        <p>
            Pengguna wajib menggunakan aplikasi secara bertanggung jawab
            dan tidak menggunakan layanan untuk aktivitas yang melanggar
            hukum atau merugikan pihak lain.
        </p>

        <h2>3. Integrasi Media Sosial</h2>
        <p>
            Pengguna yang menghubungkan akun media sosial harus mematuhi
            ketentuan dan kebijakan platform terkait.
        </p>

        <h2>4. Keamanan Akun</h2>
        <p>
            Pengguna bertanggung jawab menjaga kerahasiaan kata sandi
            dan informasi akses akun masing-masing.
        </p>

        <h2>5. Ketersediaan Layanan</h2>
        <p>
            Beberapa fitur dapat mengalami gangguan atau pemeliharaan
            sewaktu-waktu.
        </p>

        <h2>6. Perubahan Ketentuan</h2>
        <p>
            Ketentuan layanan dapat diperbarui sesuai perkembangan
            aplikasi. Pengguna disarankan memeriksa halaman ini secara
            berkala.
        </p>

        <a class="back" href="{{ route('login') }}">
            &larr; Kembali ke Login
        </a>
    </main>
</body>
</html>
