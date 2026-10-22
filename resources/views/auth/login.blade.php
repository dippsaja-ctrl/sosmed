
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Posting Sosmed</title>

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
            width: 280px;
            height: 280px;
        }

        .login-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 400px;
            padding: 18px 29px 20px;
            background: #f8faff;
            border-radius: 20px;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.12);
        }

        .login-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .login-header h1 {
            color: #24283f;
            font-size: 25px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #8290aa;
            font-size: 11px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 9px;
        }

        .form-group label {
            display: block;
            color: #34415d;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            height: 38px;
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

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin: 10px 0 13px;
            font-size: 11px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #71809a;
        }

        .remember input {
            accent-color: #4775bd;
        }

        .form-options a,
        .register-text a {
            color: #3265b7;
            text-decoration: none;
        }

        .form-options a:hover,
        .register-text a:hover {
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            height: 39px;
            border: none;
            border-radius: 9px;
            background: linear-gradient(100deg, #588bd5, #416bb3);
            color: white;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.2s;
        }

        .login-button:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .login-button:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .error-message {
            padding: 9px 11px;
            margin-bottom: 12px;
            border: 1px solid #f1c6c6;
            border-radius: 7px;
            background: #fff0f0;
            color: #b42318;
            font-size: 12px;
            line-height: 1.5;
        }

        .success-message {
            padding: 9px 11px;
            margin-bottom: 12px;
            border: 1px solid #b8e2c5;
            border-radius: 7px;
            background: #effbf2;
            color: #21713b;
            font-size: 12px;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 12px 0 14px;
            color: #8491a8;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: "";
            height: 1px;
            flex: 1;
            background: #dfe5ef;
        }

        .social-login {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 48px;
            margin-bottom: 16px;
        }

        .social-login a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 24px;
            color: #70819d;
            text-decoration: none;
            font-size: 23px;
            font-weight: bold;
            transition: color 0.2s, transform 0.2s;
        }

        .social-login a:hover {
            color: #416fba;
            transform: translateY(-2px);
        }

        .social-login .music-icon {
            font-size: 21px;
        }

        .register-text {
            text-align: center;
            color: #7d8aa1;
            font-size: 12px;
            margin-bottom: 0;
        }

        .register-text a {
            font-weight: 600;
        }

        .legal-links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            padding-top: 12px;
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
            .login-card {
                max-width: 400px;
                padding: 24px 22px;
                border-radius: 17px;
            }

            .login-header h1 {
                font-size: 23px;
            }

            .social-login {
                gap: 40px;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">

        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>
                Lanjutkan perjalananmu.<br>
                Jalanmu sudah menunggu.
            </p>
        </div>

        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

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
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <div class="form-options">
                <label class="remember">
                    <input type="checkbox" name="remember" value="1">
                    <span>Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="login-button">
                LOG IN
            </button>
        </form>

        <div class="divider">
            <span>Login with</span>
        </div>

        <div class="social-login">
            <a href="{{ url('/auth/facebook') }}" aria-label="Login dengan Facebook">f</a>
            <a href="{{ url('/auth/google') }}" aria-label="Login dengan Google">G</a>
            <a href="{{ url('/auth/tiktok') }}" class="music-icon" aria-label="Login dengan TikTok">【ᕷ】</a>
        </div>

        <p class="register-text">
            Belum punya akun?
            <a href="{{ Route::has('register') ? route('register') : url('/register') }}">
                Create account
            </a>
        </p>

        <div class="legal-links">
            <a href="{{ url('/privacy-policy') }}">Kebijakan Privasi</a>
            <span>•</span>
            <a href="{{ url('/terms-of-service') }}">Ketentuan Layanan</a>
        </div>

    </div>

</body>
</html>
