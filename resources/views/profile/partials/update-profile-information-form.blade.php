
<section class="info-profile-form">
    <form
        method="POST"
        action="{{ route('profile.update') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PATCH')

        <div class="photo-editor">
            <div class="photo-preview">
                <img
                    id="profile-photo-preview"
                    src="{{ $user->profile_photo
                        ? asset('storage/' . $user->profile_photo)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=102d55&color=ffffff&size=160' }}"
                    alt="Foto profil"
                >
            </div>

            <div class="photo-details">
                <strong>Foto Profil</strong>
                <p>Format JPG, PNG, atau WEBP. Maksimal 2 MB.</p>

                <label for="profile_photo" class="photo-upload-button">
                    📷 Pilih Foto
                </label>

                <input
                    type="file"
                    id="profile_photo"
                    name="profile_photo"
                    accept=".jpg,.jpeg,.png,.webp"
                    hidden
                >

                @error('profile_photo')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="info-form-field">
            <label for="name">Nama Lengkap</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                maxlength="255"
                required
                autocomplete="name"
            >
            @error('name')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="info-form-field">
            <label for="email">Alamat Email</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                maxlength="255"
                required
                autocomplete="email"
            >
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="info-form-actions">
            <button type="submit" class="profile-save-button">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <span class="profile-saved-message">
                    Perubahan berhasil disimpan.
                </span>
            @endif
        </div>
    </form>

    <style>
        .info-profile-form,
        .info-profile-form * {
            box-sizing: border-box;
        }

        .info-profile-form form {
            display: grid;
            gap: 20px;
        }

        .photo-editor {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            border: 1px solid #e7edf6;
            border-radius: 15px;
            background: #f8faff;
        }

        .photo-preview {
            flex-shrink: 0;
            width: 76px;
            height: 76px;
            overflow: hidden;
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 2px 10px #102d5526;
        }

        .photo-preview img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-details {
            min-width: 0;
        }

        .photo-details strong {
            display: block;
            margin-bottom: 5px;
            color: #172b48;
            font-size: 14px;
        }

        .photo-details p {
            margin: 0 0 10px;
            color: #8290a5;
            font-size: 11px;
            line-height: 1.6;
        }

        .photo-upload-button {
            display: inline-block;
            padding: 9px 13px;
            border-radius: 9px;
            background: #e7effb;
            color: #173e72;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .photo-upload-button:hover {
            background: #d6e5fa;
        }

        .info-form-field {
            display: grid;
            gap: 8px;
        }

        .info-form-field label {
            color: #34445e;
            font-size: 12px;
            font-weight: 700;
        }

        .info-form-field input {
            width: 100%;
            min-height: 44px;
            padding: 10px 13px;
            border: 1px solid #dce4ef;
            border-radius: 10px;
            background: #fbfcff;
            color: #172b48;
            font-size: 13px;
        }

        .info-form-field input:focus {
            border-color: #507db7;
            outline: 3px solid #507db721;
        }

        .field-error {
            margin: 4px 0 0;
            color: #dc4545 !important;
            font-size: 12px !important;
        }

        .info-form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .profile-save-button {
            padding: 12px 18px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(120deg, #102d55, #24558c);
            color: white;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
        }

        .profile-save-button:hover {
            background: #1b477e;
        }

        .profile-saved-message {
            color: #147547;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .photo-editor {
                align-items: flex-start;
                gap: 12px;
                padding: 12px;
            }

            .photo-preview {
                width: 60px;
                height: 60px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('profile_photo');
            const preview = document.getElementById('profile-photo-preview');

            if (!input || !preview) return;

            input.addEventListener('change', function () {
                const file = this.files && this.files[0];

                if (!file) return;

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                if (!allowedTypes.includes(file.type)) {
                    alert('Pilih foto JPG, PNG, atau WEBP.');
                    this.value = '';
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran foto maksimal 2 MB.');
                    this.value = '';
                    return;
                }

                preview.src = URL.createObjectURL(file);
            });
        });
    </script>
</section>
