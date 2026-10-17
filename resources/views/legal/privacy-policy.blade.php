
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Privasi - Posting Sosmed</title>
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
        <h1>Kebijakan Privasi</h1>

        <p>
            Selamat datang di Posting Sosmed. Halaman ini menjelaskan
            bagaimana informasi pengguna dikelola ketika menggunakan
            layanan Posting Sosmed.
        </p>

        <h2>1. Informasi Pengguna</h2>
        <p>
            Informasi yang dapat dikelola meliputi nama, alamat email,
            informasi akun, dan data yang diperlukan untuk menjalankan
            fitur aplikasi.
        </p>

        <h2>2. Penggunaan Informasi</h2>
        <p>
            Informasi digunakan untuk mengelola akun, menyediakan fitur,
            menjaga keamanan, dan meningkatkan kualitas layanan.
        </p>

        <h2>3. Akun Media Sosial</h2>
        <p>
            Jika pengguna menghubungkan akun media sosial, penggunaan
            data juga dapat mengikuti kebijakan penyedia platform
            media sosial yang bersangkutan.
        </p>

        <h2>4. Keamanan Informasi</h2>
        <p>
            Posting Sosmed berupaya melindungi informasi pengguna melalui
            langkah-langkah keamanan yang sesuai dengan layanan.
        </p>

        <h2>5. Pengelolaan Informasi</h2>
        <p>
            Pengguna dapat menghubungi pengelola aplikasi untuk
            menanyakan informasi mengenai data yang dikelola.
        </p>

        <h2>6. Perubahan Kebijakan</h2>
        <p>
            Kebijakan ini dapat diperbarui apabila terdapat perubahan
            fitur atau cara kerja layanan.
        </p>

        <a class="back" href="{{ route('login') }}">
            &larr; Kembali ke Login
        </a>
    </main>
</body>
</html>
