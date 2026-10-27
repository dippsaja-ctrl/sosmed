 @extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<style>
    .profile-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px;
        color: #10243b;
    }

    .profile-topbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 22px;
    }

    .profile-heading h1 {
        margin: 0 0 7px;
        font-size: 27px;
        font-weight: 750;
    }

    .profile-heading p,
    .profile-description {
        color: #718096;
        font-size: 13px;
        line-height: 1.6;
    }

    .profile-heading p {
        margin: 0;
    }

    .profile-close {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d8e2ed;
        border-radius: 12px;
        background: white;
        color: #193653;
        font-size: 27px;
        text-decoration: none;
        flex-shrink: 0;
    }

    .profile-close:hover {
        background: #feecec;
        color: #c62828;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 270px minmax(0, 1fr);
        gap: 22px;
        align-items: start;
    }

    .profile-left-column {
        display: flex;
        flex-direction: column;
        gap: 20px;
        min-width: 0;
    }

    .profile-card {
        min-width: 0;
        padding: 23px;
        box-sizing: border-box;
        background: #fff;
        border: 1px solid #e0e7ef;
        border-radius: 17px;
        box-shadow: 0 5px 18px rgba(20, 45, 75, .04);
    }

    .profile-summary {
        text-align: center;
    }

    .profile-photo-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 16px;
    }

    .profile-photo-button {
        position: relative;
        display: inline-flex;
        width: 100px;
        height: 100px;
        flex-shrink: 0;
        border-radius: 50%;
        cursor: pointer;
    }

    .profile-avatar {
        display: block;
        width: 100px;
        height: 100px;
        box-sizing: border-box;
        border: 4px solid #f0f6fd;
        border-radius: 50%;
        background: #e7f0fb;
        color: #1765ad;
        font-size: 42px;
        font-weight: 750;
        object-fit: cover;
        overflow: hidden;
    }

    .profile-avatar-initial {
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        text-transform: uppercase;
    }

    .profile-avatar[hidden],
    .profile-avatar-initial[hidden],
    .profile-photo-hint[hidden] {
        display: none !important;
    }

    .profile-pencil {
        position: absolute;
        right: -2px;
        bottom: 1px;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #fff;
        border-radius: 50%;
        background: #1265ad;
        color: white;
        font-size: 20px;
        line-height: 1;
    }

    .profile-photo-button:hover .profile-pencil {
        background: #0d508d;
    }

    .profile-photo-hint {
        margin: 12px 0 0;
        color: #718096;
        font-size: 12px;
        overflow-wrap: anywhere;
        text-align: center;
    }

    .profile-summary h2 {
        margin: 0 0 5px;
        font-size: 19px;
        overflow-wrap: anywhere;
    }

    .profile-email {
        color: #7b8798;
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .profile-tag {
        display: inline-block;
        margin-top: 15px;
        padding: 6px 12px;
        border-radius: 20px;
        background: #e9f5ee;
        color: #28764a;
        font-size: 12px;
        font-weight: 600;
    }

    .profile-card h3 {
        margin: 0 0 7px;
        font-size: 17px;
        font-weight: 750;
    }

    .profile-description {
        margin: 0 0 19px;
    }

    .profile-settings {
        padding: 25px;
    }

    .profile-settings-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 25px;
    }

    .profile-section {
        min-width: 0;
    }

    .profile-section + .profile-section {
        padding-left: 25px;
        border-left: 1px solid #e7edf4;
    }

    .profile-form label.field-label {
        display: block;
        margin: 0 0 8px;
        font-size: 13px;
        font-weight: 650;
    }

    .profile-form input:not([type="hidden"]):not([type="file"]) {
        display: block;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 17px;
        padding: 11px 12px;
        border: 1px solid #dbe3ed;
        border-radius: 9px;
        background: white;
        color: #10243b;
        font-size: 14px;
        outline: none;
    }

    .profile-form input:focus {
        border-color: #2878c7;
        box-shadow: 0 0 0 3px rgba(40, 120, 199, .1);
    }

    .profile-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 10px 15px;
        border: 0;
        border-radius: 9px;
        background: #1265ad;
        color: white;
        font-size: 13px;
        font-weight: 650;
        cursor: pointer;
        text-align: center;
        transition: background .2s ease;
    }

    .profile-button:hover {
        background: #0d508d;
    }

    .profile-button-danger {
        background: #c93636;
    }

    .profile-button-danger:hover {
        background: #a82424;
    }

    .profile-delete-photo-form {
        margin-top: 13px;
    }

    .profile-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #e7edf4;
    }

    .profile-actions .profile-button {
        flex: 1 1 180px;
    }

    .profile-danger-card {
        border-color: #f0dada;
    }

    .profile-danger-card h3 {
        color: #b42318;
    }

    .profile-danger-note {
        margin: 0 0 17px;
        padding: 12px;
        border: 1px solid #f2dada;
        border-radius: 9px;
        background: #fff7f7;
        color: #8f3434;
        font-size: 12px;
        line-height: 1.6;
    }

    .profile-error {
        margin: -11px 0 14px;
        color: #c62828;
        font-size: 12px;
    }

    .profile-success {
        margin-bottom: 20px;
        padding: 12px 15px;
        border-radius: 9px;
        background: #e9f8ef;
        color: #247344;
        font-size: 14px;
    }

    @media (max-width: 1000px) {
        .profile-grid {
            grid-template-columns: 235px minmax(0, 1fr);
            gap: 16px;
        }

        .profile-card,
        .profile-settings {
            padding: 18px;
        }

        .profile-settings-row {
            grid-template-columns: 1fr;
            gap: 23px;
        }

        .profile-section + .profile-section {
            padding: 23px 0 0;
            border-left: none;
            border-top: 1px solid #e7edf4;
        }
    }

    @media (max-width: 760px) {
        .profile-page {
            padding: 18px 13px;
        }

        .profile-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .profile-heading h1 {
            font-size: 23px;
        }

        .profile-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .profile-actions .profile-button {
            width: 100%;
            flex-basis: auto;
        }
    }
</style>

<div class="profile-page">
    <div class="profile-topbar">
        <div class="profile-heading">
            <h1>Profil Saya</h1>
            <p>Kelola informasi akun dan keamanan akun Posting Sosmed kamu.</p>
        </div>

        <a href="{{ route('dashboard') }}"
           class="profile-close"
           title="Tutup profil"
           aria-label="Tutup profil">&times;</a>
    </div>

    @if (session('status'))
        <div class="profile-success">
            @if (session('status') === 'profile-updated')
                Profil berhasil diperbarui.
            @elseif (session('status') === 'password-updated')
                Password berhasil diperbarui.
            @else
                {{ session('status') }}
            @endif
        </div>
    @endif

    @if ($errors->any())
        <div class="profile-success" style="background:#fff0f0;color:#a82424;">
            Periksa kembali data yang kamu masukkan. Foto harus berformat JPG, PNG, atau WEBP dan maksimal 2 MB.
        </div>
    @endif

    <div class="profile-grid">

        {{-- KOLOM KIRI: FOTO PROFIL DAN HAPUS AKUN --}}
        <div class="profile-left-column">

            <div class="profile-card profile-summary">
                <div class="profile-photo-wrapper">
                    <label for="profile_photo"
                           class="profile-photo-button"
                           title="Klik untuk mengganti foto">

                        <span class="profile-avatar profile-avatar-initial"
                              id="profileInitial"
                              @if ($user->profile_photo) hidden @endif>{{ mb_strtoupper(mb_substr(trim($user->name), 0, 1, 'UTF-8'), 'UTF-8') ?: '?' }}</span>

                        <img class="profile-avatar"
                             id="profilePreview"
                             src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : '' }}"
                             alt="Foto profil"
                             @if (!$user->profile_photo) hidden @endif>

                        <span class="profile-pencil" aria-hidden="true">✎</span>
                    </label>

                    <p class="profile-photo-hint">Klik ikon pensil untuk mengganti foto</p>
                    <p id="photoFileName" class="profile-photo-hint" hidden></p>
                </div>

                <h2 id="profileDisplayName">{{ $user->name }}</h2>
                <div class="profile-email" id="profileDisplayEmail">{{ $user->email }}</div>
                <span class="profile-tag">Akun aktif</span>

                @if ($user->profile_photo)
                    <form class="profile-delete-photo-form"
                          method="POST"
                          action="{{ route('profile.photo.delete') }}"
                          onsubmit="return confirm('Hapus foto profil dan kembalikan avatar ke huruf awal nama?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="profile-button profile-button-danger">
                            Hapus Foto Profil
                        </button>
                    </form>
                @endif
            </div>

            {{-- HAPUS AKUN TERPISAH DI BAWAH FOTO PROFIL --}}
            <div class="profile-card profile-danger-card">
                <h3>Hapus Akun</h3>
                <p class="profile-description">
                    Hapus akun Posting Sosmed jika kamu tidak ingin menggunakannya lagi.
                </p>

                <div class="profile-danger-note">
                    Tindakan ini permanen dan dapat menghapus data akun kamu.
                </div>

                <form class="profile-form"
                      method="POST"
                      action="{{ route('profile.destroy') }}"
                      onsubmit="return confirm('Apakah kamu yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')

                    <label class="field-label" for="delete_password">
                        Konfirmasi Password
                    </label>
                    <input type="password"
                           id="delete_password"
                           name="password"
                           autocomplete="current-password"
                           required>

                    @foreach ($errors->get('userDeletion.password') as $error)
                        <div class="profile-error">{{ $error }}</div>
                    @endforeach

                    <button class="profile-button profile-button-danger" type="submit">
                        Hapus Akun Saya
                    </button>
                </form>
            </div>

        </div>

        {{-- KOLOM KANAN: SATU KOTAK PENGATURAN --}}
        <div class="profile-card profile-settings">

            <div class="profile-settings-row">

                {{-- FORM INFORMASI PROFIL --}}
                <section class="profile-section">
                    <h3>Informasi Profil</h3>
                    <p class="profile-description">
                        Perbarui nama dan alamat email akun kamu.
                    </p>

                    <form class="profile-form"
                          id="profileForm"
                          method="POST"
                          action="{{ route('profile.update') }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <input type="file"
                               id="profile_photo"
                               name="profile_photo"
                               accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                               hidden>

                        @error('profile_photo')
                            <div class="profile-error">{{ $message }}</div>
                        @enderror

                        <label class="field-label" for="name">Nama Lengkap</label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               maxlength="255"
                               required>

                        @error('name')
                            <div class="profile-error">{{ $message }}</div>
                        @enderror

                        <label class="field-label" for="email">Alamat Email</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               maxlength="255"
                               required>

                        @error('email')
                            <div class="profile-error">{{ $message }}</div>
                        @enderror
                    </form>
                </section>

                {{-- FORM UBAH PASSWORD --}}
                <section class="profile-section">
                    <h3>Ubah Password</h3>
                    <p class="profile-description">
                        Gunakan password saat ini untuk mengganti password akun.
                    </p>

                    <form class="profile-form"
                          id="passwordForm"
                          method="POST"
                          action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <label class="field-label" for="current_password">
                            Password Saat Ini
                        </label>
                        <input type="password"
                               id="current_password"
                               name="current_password"
                               autocomplete="current-password"
                               required>

                        @foreach ($errors->get('current_password') as $error)
                            <div class="profile-error">{{ $error }}</div>
                        @endforeach

                        <label class="field-label" for="password">Password Baru</label>
                        <input type="password"
                               id="password"
                               name="password"
                               autocomplete="new-password"
                               required>

                        @foreach ($errors->get('password') as $error)
                            <div class="profile-error">{{ $error }}</div>
                        @endforeach

                        <label class="field-label" for="password_confirmation">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               autocomplete="new-password"
                               required>
                    </form>
                </section>

            </div>

            {{-- KEDUA TOMBOL BERDAMPINGAN --}}
            <div class="profile-actions">
                <button class="profile-button"
                        type="submit"
                        form="profileForm">
                    Simpan Perubahan
                </button>

                <button class="profile-button"
                        type="submit"
                        form="passwordForm">
                    Perbarui Password
                </button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const photoInput = document.getElementById('profile_photo');

    const initial = document.getElementById('profileInitial');
    const preview = document.getElementById('profilePreview');
    const displayName = document.getElementById('profileDisplayName');
    const displayEmail = document.getElementById('profileDisplayEmail');
    const fileName = document.getElementById('photoFileName');

    let previewUrl = null;

    function updateInitial() {
        if (!nameInput || !initial) return;

        const name = nameInput.value.trim();

        initial.textContent = name
            ? Array.from(name)[0].toLocaleUpperCase('id-ID')
            : '?';

        if (displayName) {
            displayName.textContent = name || 'Nama pengguna';
        }
    }

    if (nameInput) {
        nameInput.addEventListener('input', updateInitial);
    }

    if (emailInput) {
        emailInput.addEventListener('input', function () {
            if (displayEmail) {
                displayEmail.textContent = this.value || 'Email belum diisi';
            }
        });
    }

    if (photoInput) {
        photoInput.addEventListener('change', function () {
            const file = this.files && this.files[0];

            if (!file) return;

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {
                alert('Pilih foto berformat JPG, PNG, atau WEBP.');
                this.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran foto maksimal 2 MB.');
                this.value = '';
                return;
            }

            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }

            previewUrl = URL.createObjectURL(file);
            preview.src = previewUrl;
            preview.hidden = false;

            if (initial) {
                initial.hidden = true;
            }

            if (fileName) {
                fileName.textContent = 'Foto dipilih: ' + file.name;
                fileName.hidden = false;
            }
        });
    }

    updateInitial();
});
</script>
@endsection