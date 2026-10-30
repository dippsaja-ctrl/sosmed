<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - Posting Sosmed</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #050b16, #102d55);
            color: #14233a;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 28px;
            background: #f7f9ff;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }

        .login-logo img {
            display: block;
            width: 100px;
            height: 85px;
            object-fit: contain;
            object-position: center;
        }

        h1 {
            margin: 8px 0;
            text-align: center;
            font-size: 25px;
            color: #102d55;
        }

        .subtitle {
            margin: 8px 0 24px;
            text-align: center;
            color: #71809a;
            font-size: 13px;
            line-height: 1.7;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            height: 43px;
            padding: 0 13px;
            border: 1px solid #d4deef;
            border-radius: 10px;
            outline: none;
            background: #fff;
            color: #14233a;
            font-size: 13px;
        }

        .form-group input:focus {
            border-color: #5686d5;
            box-shadow: 0 0 0 3px rgba(86, 134, 213, 0.12);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 48px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 6px;
            background: transparent;
            color: #71809a;
            cursor: pointer;
        }

        .toggle-password:hover {
            color: #4168b7;
            background: #edf3ff;
        }

        .toggle-password svg {
            width: 21px;
            height: 21px;
            display: block;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin: 3px 0 18px;
            font-size: 11px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .remember input {
            margin: 0;
        }

        a {
            color: #1556bd;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .login-button {
            display: block;
            width: 100%;
            height: 43px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(100deg, #5688db, #4168b7);
            color: white;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .login-button:hover {
            opacity: 0.9;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 19px 0;
            color: #71809a;
            font-size: 11px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #dce3ef;
        }

        .register-text {
            margin: 0;
            text-align: center;
            font-size: 12px;
            color: #71809a;
        }

        .legal-links {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #dce3ef;
            font-size: 10px;
        }

        .error-message {
            margin: 0 0 14px;
            padding: 10px 12px;
            border-radius: 8px;
            background: #fff0f0;
            color: #b42318;
            font-size: 12px;
        }

        @media (max-width: 420px) {
            .login-card {
                padding: 24px 20px;
            }

            .login-logo img {
                width: 90px;
                height: 78px;
            }
        }
    </style>
</head>

<body>
    <main class="login-card">

        <div class="login-logo">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="Logo Posting Sosmed"
            >
        </div>

        <h1>Welcome Back</h1>

        <p class="subtitle">
            Lanjutkan perjalananmu.<br>
            Jalanmu sudah menunggu.
        </p>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login', absolute: false) }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="nama@gmail.com"
                    autocomplete="email"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword()"
                        aria-label="Tampilkan password"
                        title="Tampilkan password"
                    >
                        <svg
                            id="eyeIcon"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="form-options">
                <label class="remember">
                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                    >
                    <span>Remember me</span>
                </label>

                <a href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            </div>

            <button type="submit" class="login-button">
                LOG IN
            </button>
        </form>

        <div class="divider">Login with</div>

        <p class="register-text">
            Belum punya akun?
            <a href="{{ route('register') }}">
                <strong>Create account</strong>
            </a>
        </p>

        <div class="legal-links">
            <a href="{{ route('legal.privacy') }}">
                Kebijakan Privasi
            </a>

            <span>-</span>

            <a href="{{ route('legal.terms') }}">
                Ketentuan Layanan
            </a>
        </div>

    </main>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const button = document.querySelector('.toggle-password');

            if (password.type === 'password') {
                password.type = 'text';

                eyeIcon.innerHTML = `
                    <path d="M3 3l18 18"/>
                    <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                    <path d="M9.9 5.2A10.8 10.8 0 0 1 12 5
                    c6.4 0 10 7 10 7a15.8 15.8 0 0 1-3 3.8"/>
                    <path d="M6.6 6.6C3.7 8.5 2 12 2 12
                    s3.6 7 10 7a10.8 10.8 0 0 0 4-.8"/>
                `;

                button.setAttribute('aria-label', 'Sembunyikan password');
                button.setAttribute('title', 'Sembunyikan password');
            } else {
                password.type = 'password';

                eyeIcon.innerHTML = `
                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                    <circle cx="12" cy="12" r="3"/>
                `;

                button.setAttribute('aria-label', 'Tampilkan password');
                button.setAttribute('title', 'Tampilkan password');
            }
        }
    </script>
</body>
</html>
