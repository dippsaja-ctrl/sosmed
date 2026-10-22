
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Posting Sosmed</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px 15px;
            position: relative;
            overflow-x: hidden;
            background: linear-gradient(120deg, #061020, #10294b);
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(55, 91, 145, 0.18);
            pointer-events: none;
        }

        body::before {
            top: -120px;
            left: -100px;
        }

        body::after {
            bottom: -130px;
            right: -60px;
        }

        .register-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 27px 30px 22px;
            background: #f8faff;
            border-radius: 20px;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.12);
        }

        .register-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .register-header h1 {
            color: #24283f;
            font-size: 25px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .register-header p {
            color: #8290aa;
            font-size: 12px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 13px;
        }

        .form-group label {
            display: block;
            color: #34415d;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-group input {
            width: 100%;
            height: 41px;
            padding: 0 13px;
            border: 1px solid #dce3ef;
            border-radius: 8px;
            outline: none;
            background: #fff;
            color: #29334b;
            font-size: 12px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            border-color: #5486cf;
            box-shadow: 0 0 0 3px rgba(84, 134, 207, 0.12);
        }

        .form-group input::placeholder {
            color: #a0aec4;
        }

        .password-hint {
            display: block;
            color: #8995aa;
            font-size: 10px;
            margin-top: 6px;
            line-height: 1.5;
        }

        .agreement {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin: 17px 0;
            color: #687790;
            font-size: 11px;
            line-height: 1.7;
        }

        .agreement input {
            flex-shrink: 0;
            margin-top: 3px;
            accent-color: #4775bd;
            cursor: pointer;
        }

        .agreement a {
            color: #3265b7;
            text-decoration: none;
        }

        .agreement a:hover {
            text-decoration: underline;
        }

        .register-button {
            width: 100%;
            height: 42px;
            border: none;
            border-radius: 9px;
            background: linear-gradient(100deg, #588bd5, #416bb3);
            color: white;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.2s;
        }

        .register-button:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .register-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            padding: 10px 12px;
            margin-bottom: 14px;
            border: 1px solid #f1c6c6;
            border-radius: 7px;
            background: #fff0f0;
            color: #b42318;
            font-size: 12px;
            line-height: 1.6;
        }

        .login-text {
            margin-top: 18px;
            text-align: center;
            color: #7d8aa1;
            font-size: 12px;
        }

        .login-text a {
            color: #3265b7;
            font-weight: 600;
            text-decoration: none;
        }

        .login-text a:hover {
            text-decoration: underline;
        }

        .legal-links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
            padding-top: 13px;
            border-top: 1px solid #e1e7f1;
            color: #9aa5b8;
            font-size: 10px;
        }

        .legal-links a {
            color: #5278b8;
            text-decoration: none;
        }

        .legal-links a:hover {
            color: #244f98;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .register-card {
                padding: 24px 22px 20px;
                border-radius: 17px;
            }

            .register-header h1 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

    <div class="register-card">

        <div class="register-header">
            <h1>Create Account</h1>
            <p>
                Bergabunglah dengan Posting Sosmed.<br>
                Buat akun untuk memulai perjalananmu.
            </p>
        </div>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register', absolute: false) }}">
            @csrf

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap"
                    autocomplete="name"
                    required
                    autofocus
                >
            </div>

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
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Buat password"
                    autocomplete="new-password"
                    required
                >
                <span class="password-hint">
                    Gunakan password yang kuat dan jangan bagikan kepada orang lain.
                </span>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Masukkan ulang password"
                    autocomplete="new-password"
                    required
                >
            </div>

            <label class="agreement">
                <input
                    type="checkbox"
                    name="agree_to_terms"
                    value="1"
                    required
                >
                <span>
                    Saya menyetujui
                    <a href="{{ url('/terms-of-service') }}" target="_blank">
                        Ketentuan Layanan
                    </a>
                    dan telah membaca
                    <a href="{{ url('/privacy-policy') }}" target="_blank">
                        Kebijakan Privasi
                    </a>.
                </span>
            </label>

            <button type="submit" class="register-button">
                CREATE ACCOUNT
            </button>
        </form>

        <p class="login-text">
            Sudah punya akun?
            <a href="{{ route('login') }}">Login</a>
        </p>

        <div class="legal-links">
            <a href="{{ url('/privacy-policy') }}">Kebijakan Privasi</a>
            <span>•</span>
            <a href="{{ url('/terms-of-service') }}">Ketentuan Layanan</a>
        </div>

    </div>

</body>
</html>
