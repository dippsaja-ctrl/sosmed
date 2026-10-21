
<section class="delete-profile-form">
    <p class="delete-description">
        Jika akun dihapus, kamu bisa kehilangan akses ke akun dan data
        yang terkait. Pastikan kamu benar-benar ingin melanjutkan.
    </p>

    <form
        method="POST"
        action="{{ route('profile.destroy') }}"
        onsubmit="return confirm('Apakah kamu yakin ingin menghapus akun secara permanen? Tindakan ini tidak dapat dibatalkan.');"
    >
        @csrf
        @method('DELETE')

        <div class="delete-password-field">
            <label for="delete_password">Kata Sandi Saat Ini</label>
            <input
                id="delete_password"
                name="password"
                type="password"
                autocomplete="current-password"
                required
            >

            @if ($errors->userDeletion->has('password'))
                <p class="delete-error">
                    {{ $errors->userDeletion->first('password') }}
                </p>
            @endif
        </div>

        <button type="submit" class="delete-account-button">
            Hapus Akun Saya
        </button>
    </form>

    <style>
        .delete-description {
            margin: 0 0 18px;
            color: #748198;
            font-size: 12px;
            line-height: 1.8;
        }

        .delete-password-field {
            display: grid;
            gap: 8px;
            margin-bottom: 14px;
        }

        .delete-password-field label {
            color: #34445e;
            font-size: 12px;
            font-weight: 700;
        }

        .delete-password-field input {
            box-sizing: border-box;
            width: 100%;
            min-height: 43px;
            padding: 10px 12px;
            border: 1px solid #ead8d7;
            border-radius: 9px;
            font-size: 13px;
        }

        .delete-error {
            margin: 0;
            color: #dc4545;
            font-size: 12px;
        }

        .delete-account-button {
            width: 100%;
            padding: 12px 15px;
            border: 0;
            border-radius: 10px;
            background: #c83e46;
            color: white;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
        }

        .delete-account-button:hover {
            background: #a92e36;
        }
    </style>
</section>
