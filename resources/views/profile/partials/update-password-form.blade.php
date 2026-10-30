
<section class="password-profile-form">
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="password-field">
            <label for="current_password">Kata Sandi Saat Ini</label>
            <input
                id="current_password"
                name="current_password"
                type="password"
                autocomplete="current-password"
                required
            >
            @if ($errors->updatePassword->has('current_password'))
                <p class="password-error">
                    {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif
        </div>

        <div class="password-field">
            <label for="password">Kata Sandi Baru</label>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="new-password"
                required
            >
            @if ($errors->updatePassword->has('password'))
                <p class="password-error">
                    {{ $errors->updatePassword->first('password') }}
                </p>
            @endif
        </div>

        <div class="password-field">
            <label for="password_confirmation">
                Konfirmasi Kata Sandi Baru
            </label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                required
            >
        </div>

        <div class="password-actions">
            <button type="submit" class="password-save-button">
                Ubah Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <span class="password-success">
                    Kata sandi berhasil diperbarui.
                </span>
            @endif
        </div>
    </form>

    <style>
        .password-profile-form form {
            display: grid;
            gap: 18px;
        }

        .password-field {
            display: grid;
            gap: 8px;
        }

        .password-field label {
            color: #34445e;
            font-size: 12px;
            font-weight: 700;
        }

        .password-field input {
            box-sizing: border-box;
            width: 100%;
            min-height: 44px;
            padding: 10px 13px;
            border: 1px solid #dce4ef;
            border-radius: 10px;
            background: #fbfcff;
            color: #172b48;
            font-size: 13px;
        }

        .password-field input:focus {
            border-color: #507db7;
            outline: 3px solid #507db721;
        }

        .password-error {
            margin: 0;
            color: #dc4545;
            font-size: 12px;
        }

        .password-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .password-save-button {
            padding: 12px 18px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(120deg, #102d55, #24558c);
            color: white;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
        }

        .password-success {
            color: #147547;
            font-size: 12px;
        }
    </style>
</section>
