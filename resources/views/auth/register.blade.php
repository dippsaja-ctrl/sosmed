<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - Pos Bersama</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(
                135deg,
                #050b16 0%,
                #09172b 48%,
                #102d55 100%
            );
            overflow-x: hidden;
        }

        /* Dekorasi background */
        body::before,
        body::after {
            content: "";
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        body::before {
            width: 280px;
            height: 280px;
            top: -120px;
            left: -100px;
            background: rgba(86, 135, 200, 0.12);
        }

        body::after {
            width: 320px;
            height: 320px;
            bottom: -170px;
            right: -100px;
            background: rgba(86, 135, 200, 0.10);
        }

        /* Kartu register */
        .register-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 25px 32px 24px;
            background: #f8faff;
            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 20px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.30);
        }

        /* Area logo */
        .register-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 12px;
        }

        .register-logo img {
            display: block;
            width: 90px;
            height: 90px;
            object-fit: contain;
        }

        /* Logo disembunyikan jika file tidak tersedia */
        .register-logo img:not([src]),
        .register-logo img[src=""] {
            display: none;
        }

        /* Judul */
        .register-header {
            text-align: center;
            margin-bottom: 21px;
        }

        .register-header h1 {
            color: #20233c;
            font-size: 29px;
            line-height: 1.25;
            font-weight: 700;
            margin-bottom: 9px;
        }

        .register-header p {
            color: #8991a0;
            font-size: 13px;
            line-height: 1.7;
        }

        /* Form */
        .form-group {
            margin-bottom: 13px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #525b6b;
            font-size: 13px;
            font-weight: 600;
        }

        .input-box {
            position: relative;
        }

        .input-box input {
            display: block;
            width: 100%;
            height: 43px;
            padding: 0 14px;
            border: 1px solid #e1e5ed;
            border-radius: 8px;
            outline: none;
            background: #fff;
            color: #263247;
            font-family: inherit;
            font-size: 13px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-box input::placeholder {
            color: #b0b6c1;
        }

        .input-box input:focus {
            border-color: #4f79bd;
            box-shadow: 0 0 0 3px rgba(79, 121, 189, 0.10);
        }

        .password-input {
            padding-right: 48px !important;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #65738a;
            cursor: pointer;
            font-size: 16px;
        }

        .error-message {
            margin-top: 5px;
            color: #dc3545;
            font-size: 11px;
        }

        /* Tombol daftar */
        .register-button {
            display: block;
            width: 100%;
            height: 45px;
            margin-top: 15px;
            border: none;
            border-radius: 9px;
            background: linear-gradient(135deg, #588bd2, #416bb0);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }

        .register-button:hover {
            background: linear-gradient(135deg, #477bc3, #315a9c);
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(65, 107, 176, 0.18);
        }

        /* Pemisah */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 14px 0 10px;
            color: #a0a7b3;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e4e7ed;
        }

        /* Link login */
        .login-link {
            text-align: center;
            color: #8b93a1;
            font-size: 13px;
        }

        .login-link a {
            color: #416bb0;
            font-weight: 700;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Ikon sosial diperbesar */
        .social-links {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 35px;
            margin-top: 12px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            color: #727b8b;
            font-size: 28px;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s, transform 0.2s;
        }

        .social-links a:hover {
            color: #416bb0;
            transform: scale(1.15);
        }

        /* Laptop dengan layar pendek */
        @media (max-height: 850px) {
            .register-card {
                max-width: 400px;
                padding: 15px 28px;
            }

            .register-logo {
                margin-bottom: 6px;
            }

            .register-logo img {
                width: 65px;
                height: 65px;
            }

            .register-header {
                margin-bottom: 12px;
            }

            .register-header h1 {
                font-size: 25px;
                margin-bottom: 6px;
            }

            .register-header p {
                font-size: 11px;
                line-height: 1.5;
            }

            .form-group {
                margin-bottom: 9px;
            }

            .form-group label {
                margin-bottom: 4px;
            }

            .input-box input {
                height: 37px;
            }

            .register-button {
                height: 39px;
                margin-top: 10px;
            }

            .divider {
                margin: 9px 0 6px;
            }

            .social-links {
                margin-top: 8px;
                gap: 30px;
            }

            .social-links a {
                width: 38px;
                height: 38px;
                font-size: 26px;
            }
        }

        /* HP */
        @media (max-width: 420px) {
            body {
                padding: 12px;
            }

            .register-card {
                max-width: 390px;
                padding: 22px 21px;
            }

            .register-header h1 {
                font-size: 25px;
            }

            .social-links {
                gap: 28px;
            }

            .social-links a {
                width: 36px;
                height: 36px;
                font-size: 25px;
            }
        }

        /* HP dengan layar pendek */
        @media (max-width: 420px) and (max-height: 700px) {
            .register-card {
                padding: 12px 19px;
            }

            .register-logo img {
                width: 48px;
                height: 48px;
            }

            .register-header {
                margin-bottom: 9px;
            }

            .register-header h1 {
                font-size: 22px;
            }

            .form-group {
                margin-bottom: 7px;
            }

            .input-box input {
                height: 34px;
            }

            .register-button {
                height: 36px;
                margin-top: 8px;
            }

            .divider {
                margin: 7px 0 5px;
            }

            .social-links {
                margin-top: 6px;
                gap: 25px;
            }

            .social-links a {
                width: 34px;
                height: 34px;
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <main class="register-card">

        <!-- Logo: tidak menampilkan tulisan jika gambar gagal dimuat -->
        <div class="register-logo">
            <img
                src="{{ asset('images/logo.png') }}"
                alt=""
                onerror="this.style.display='none'; this.parentElement.style.display='none';"
            >
        </div>

        <div class="register-header">
            <h1>Create your Account</h1>

            <p>
                Posting video ke seluruh platform dengan sangat mudah.
                <br>
                Are you ready?
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nama -->
            <div class="form-group">
                <label for="name">Name</label>

                <div class="input-box">
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Nama lengkap"
                        autocomplete="name"
                        required
                        autofocus
                    >
                </div>

                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>

                <div class="input-box">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@gmail.com"
                        autocomplete="email"
                        required
                    >
                </div>

                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>

                <div class="input-box">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="password-input"
                        placeholder="Minimal 8 karakter"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword('password', this)"
                        aria-label="Tampilkan atau sembunyikan password"
                    >◉</button>
                </div>

                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Konfirmasi password -->
            <div class="form-group">
                <label for="password_confirmation">Confirm password</label>

                <div class="input-box">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="password-input"
                        placeholder="Ulang password"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword('password_confirmation', this)"
                        aria-label="Tampilkan atau sembunyikan konfirmasi password"
                    >◉</button>
                </div>
            </div>

            <button type="submit" class="register-button">
                GET STARTED
            </button>
        </form>

        <div class="divider">
            <span>Login with</span>
        </div>

        <div class="login-link">
            Sudah punya akun?
            <a href="{{ route('login') }}">Log in</a>
        </div>

        <div class="social-links">
            <a href="{{ route('login') }}" aria-label="Facebook">f</a>
            <a href="{{ route('login') }}" aria-label="Google">G</a>
            <a href="{{ route('login') }}" aria-label="TikTok">♪</a>
        </div>

    </main>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);

            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '◎';
            } else {
                input.type = 'password';
                button.textContent = '◉';
            }
        }
    </script>

</body>
</html>