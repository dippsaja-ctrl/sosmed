
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Posting Sosmed</title>

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
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow-x: hidden;
            position: relative;
            background: linear-gradient(120deg, #050b16 0%, #09172b 52%, #102d55 100%);
            color: #252b45;
        }

        /* Ornamen lingkaran seperti halaman login */
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
            background: rgba(67, 105, 157, 0.13);
            top: -120px;
            left: -100px;
        }

        body::after {
            width: 330px;
            height: 330px;
            background: rgba(67, 126, 201, 0.16);
            bottom: -180px;
            right: -110px;
        }

        .forgot-container {
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
        }

        .forgot-card {
            background: #f7f9ff;
            border-radius: 20px;
            padding: 30px;
            width: 100%;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 25px;
            font-weight: 700;
            color: #252941;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 12px;
            color: #78839d;
            line-height: 1.7;
        }

        .form-group {
            margin-bottom: 17px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #303955;
            margin-bottom: 7px;
        }

        .form-group input {
            display: block;
            width: 100%;
            height: 42px;
            border: 1px solid #dce2ef;
            border-radius: 9px;
            background: #ffffff;
            padding: 0 14px;
            font-size: 13px;
            color: #252b45;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input::placeholder {
            color: #a2aec5;
        }

        .form-group input:focus {
            border-color: #5788d3;
            box-shadow: 0 0 0 3px rgba(87, 136, 211, 0.13);
        }

        .message {
            font-size: 12px;
            line-height: 1.6;
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 17px;
            overflow-wrap: anywhere;
        }

        .success {
            background: #e7f7ed;
            color: #237545;
            border: 1px solid #c4ebd1;
        }

        .error {
            background: #fff0f0;
            color: #b42332;
            border: 1px solid #f5cccc;
        }

        .error ul {
            padding-left: 18px;
        }

        .submit-button {
            width: 100%;
            height: 42px;
            border: none;
            border-radius: 9px;
            background: linear-gradient(100deg, #568bd8, #416bb5);
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.4px;
            cursor: pointer;
            transition: 0.2s;
        }

        .submit-button:hover {
            background: linear-gradient(100deg, #477bc6, #345ba0);
            transform: translateY(-1px);
        }

        .back-login {
            text-align: center;
            margin-top: 22px;
            font-size: 12px;
            color: #7c879f;
        }

        .back-login a {
            color: #3264b8;
            font-weight: 600;
            text-decoration: none;
        }

        .back-login a:hover {
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 22px;
            color: #8994ac;
            font-size: 11px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e0e5ef;
        }

        @media (max-width: 480px) {
            body {
                padding: 18px;
            }

            .forgot-card {
                padding: 25px 22px;
                border-radius: 18px;
            }

            .header h1 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>
    <main class="forgot-container">
        <section class="forgot-card">

            <div class="header">
                <h1>Forgot Password?</h1>
                <p>
                    Jangan khawatir, kami siap membantu.
                    <br>
                    Masukkan email untuk mengatur ulang password kamu.
                </p>
            </div>

            @if (session('status'))
                <div class="message success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="message error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

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
                        autofocus
                    >
                </div>

                <button type="submit" class="submit-button">
                    KIRIM LINK RESET PASSWORD
                </button>
            </form>

            <div class="divider">
                Posting Sosmed
            </div>

            <div class="back-login">
                Ingat password kamu?
                <a href="{{ route('login') }}">Log in</a>
            </div>

        </section>
    </main>
</body>
</html>
