
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account - Posting Sosmed</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background: linear-gradient(120deg, #050b16, #09172b 52%, #102d55);
            color: #252b45;
            position: relative;
            overflow-x: hidden;
        }

        .decoration {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            background: rgba(67, 126, 201, .12);
        }

        .decoration.one {
            width: 280px;
            height: 280px;
            top: -120px;
            left: -100px;
        }

        .decoration.two {
            width: 330px;
            height: 330px;
            right: -110px;
            bottom: -180px;
        }

        .auth-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
        }

        .auth-card {
            padding: 24px 30px 23px;
            border-radius: 20px;
            background: #f7f9ff;
            box-shadow: 0 12px 35px rgba(0, 0, 0, .18);
        }

        .auth-logo {
            width: 90px;
            height: 70px;
            margin: 0 auto 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-logo img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .auth-header h1 {
            font-size: 25px;
            margin-bottom: 8px;
            color: #252941;
        }

        .auth-header p {
            font-size: 12px;
            color: #7888a8;
            line-height: 1.7;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            color: #303955;
            font-size: 13px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            height: 42px;
            padding: 0 13px;
            border: 1px solid #d9e1f0;
            border-radius: 9px;
            background: #fff;
            color: #252b45;
            font-size: 13px;
            outline: none;
        }

        .form-group input:focus {
            border-color: #5788d3;
            box-shadow: 0 0 0 3px rgba(87, 136, 211, .13);
        }

        .form-group input::placeholder {
            color: #9aa9c3;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border: none;
            background: transparent;
            color: #71809b;
            cursor: pointer;
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
        }

        .password-hint {
            display: block;
            margin-top: 6px;
            color: #8491aa;
            font-size: 10px;
            line-height: 1.5;
        }

        .agreement {
            margin: 17px 0 19px;
        }

        .agreement-label {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            color: #56617c;
            font-size: 11px;
            line-height: 1.7;
            cursor: pointer;
        }

        .agreement-label input {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            margin-top: 3px;
            accent-color: #4779c5;
            cursor: pointer;
        }

        .agreement-label a {
            color: #2459ad;
            font-weight: 600;
            text-decoration: none;
        }

        .agreement-label a:hover {
            text-decoration: underline;
        }

        .submit-button {
            width: 100%;
            min-height: 42px;
            border: none;
            border-radius: 9px;
            background: linear-gradient(100deg, #568bd8, #416bb5);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .submit-button:not(:disabled):hover {
            background: linear-gradient(100deg, #477bc6, #345ba0);
        }

        .submit-button:disabled {
            background: #aabbd5;
            color: #f5f7fc;
            cursor: not-allowed;
            opacity: .75;
        }

        .login-link {
            text-align: center;
            margin-top: 18px;
            color: #7c879f;
            font-size: 12px;
        }

        .login-link a {
            color: #285db5;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 9px;
            border-top: 1px solid #e0e5ef;
            margin-top: 18px;
            padding-top: 14px;
            font-size: 10px;
        }

        .footer-links a {
            color: #3264b8;
            text-decoration: none;
        }

        .footer-links span {
            color: #a4aec1;
        }

        .error-message {
            padding: 10px 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            background: #fff0f0;
            color: #b42332;
            border: 1px solid #f0cccc;
            font-size: 12px;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .error-message ul {
            padding-left: 18px;
        }

        @media (max-width: 480px) {
            body {
                padding: 16px 12px;
            }

            .auth-card {
                padding: 23px 21px;
            }

            .auth-header h1 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>
    <div class="decoration one"></div>
    <div class="decoration two"></div>

    <main class="auth-container">
        <section class="auth-card">

            <header class="auth-header">
                <div class="auth-logo">
                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="Logo Posting Sosmed"
                    >
                </div>

                <h1>Create Account</h1>
                <p>
                    Bergabunglah dengan Posting Sosmed.
                    <br>
                    Buat akun untuk memulai perjalananmu.
                </p>
            </header>

            @if ($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('register') }}"
                  id="register-form">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap"
                        autocomplete="name"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@gmail.com"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <div class="password-wrapper">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Buat password"
                            autocomplete="new-password"
                            required
                        >

                        <button type="button"
                                class="toggle-password"
                                aria-label="Tampilkan password"
                                aria-pressed="false"
                                onclick="togglePassword('password', this)">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>

                    <small class="password-hint">
                        Gunakan password yang kuat dan jangan bagikan kepada orang lain.
                    </small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        Konfirmasi Password
                    </label>

                    <div class="password-wrapper">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="Masukkan ulang password"
                            autocomplete="new-password"
                            required
                        >

                        <button type="button"
                                class="toggle-password"
                                aria-label="Tampilkan password"
                                aria-pressed="false"
                                onclick="togglePassword('password_confirmation', this)">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="agreement">
                    <label for="terms" class="agreement-label">
                        <input
                            type="checkbox"
                            id="terms"
                            name="terms"
                            value="1"
                            required
                        >

                        <span>
                            Saya menyetujui
                            <a href="{{ url('/terms-of-service') }}"
                               target="_blank"
                               rel="noopener noreferrer">
                                Ketentuan Layanan
                            </a>
                            dan telah membaca
                            <a href="{{ url('/privacy-policy') }}"
                               target="_blank"
                               rel="noopener noreferrer">
                                Kebijakan Privasi
                            </a>.
                        </span>
                    </label>
                </div>

                <button
                    type="submit"
                    id="register-button"
                    class="submit-button"
                    disabled
                >
                    CREATE ACCOUNT
                </button>
            </form>

            <div class="login-link">
                Sudah punya akun?
                <a href="{{ route('login') }}">Login</a>
            </div>

            <footer class="footer-links">
                <a href="{{ url('/privacy-policy') }}">Kebijakan Privasi</a>
                <span>-</span>
                <a href="{{ url('/terms-of-service') }}">Ketentuan Layanan</a>
            </footer>

        </section>
    </main>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const visible = input.type === 'password';

            input.type = visible ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(visible));
            button.setAttribute(
                'aria-label',
                visible ? 'Sembunyikan password' : 'Tampilkan password'
            );

            button.innerHTML = visible
                ? `<svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3l18 18"/>
                    <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                    <path d="M9.9 5.2A10.8 10.8 0 0 1 12 5
                    c6.4 0 10 7 10 7a16 16 0 0 1-3.1 3.9"/>
                    <path d="M6.2 6.2C3.5 8 2 12 2 12
                    s3.6 7 10 7c1.2 0 2.3-.2 3.3-.6"/>
                    </svg>`
                : `<svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/>
                    <circle cx="12" cy="12" r="3"/>
                    </svg>`;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('terms');
            const button = document.getElementById('register-button');
            const form = document.getElementById('register-form');

            function updateButton() {
                button.disabled = !checkbox.checked;
            }

            checkbox.addEventListener('change', updateButton);

            form.addEventListener('submit', function (event) {
                if (!checkbox.checked) {
                    event.preventDefault();
                    updateButton();
                    checkbox.focus();
                }
            });

            updateButton();
        });
    </script>
</body>
</html>
