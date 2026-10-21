<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui nama, email, dan foto profil.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $oldPhoto = $user->profile_photo;
        $newPhoto = null;

        // Simpan foto baru ke storage/app/public/profile-photos.
        if ($request->hasFile('profile_photo')) {
            $newPhoto = $request->file('profile_photo')
                ->store('profile-photos', 'public');

            if (!$newPhoto) {
                return Redirect::route('profile.edit')
                    ->with('status', 'Foto profil gagal diunggah. Silakan coba lagi.');
            }
        }

        try {
            $user->name = $validated['name'];

            if ($user->email !== $validated['email']) {
                $user->email = $validated['email'];
                $user->email_verified_at = null;
            }

            if ($newPhoto !== null) {
                $user->profile_photo = $newPhoto;
            }

            $user->save();
        } catch (\Throwable $exception) {
            // Hapus file baru jika penyimpanan data gagal.
            if ($newPhoto !== null) {
                Storage::disk('public')->delete($newPhoto);
            }

            throw $exception;
        }

        // Hapus foto lama setelah perubahan berhasil disimpan.
        if (
            $newPhoto !== null &&
            $oldPhoto &&
            $oldPhoto !== $newPhoto
        ) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Menghapus foto profil pengguna.
     */
    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();
        $oldPhoto = $user->profile_photo;

        if (!$oldPhoto) {
            return Redirect::route('profile.edit')
                ->with('status', 'Tidak ada foto profil untuk dihapus.');
        }

        // Kosongkan foto di database agar avatar kembali ke huruf awal nama.
        $user->profile_photo = null;
        $user->save();

        // Hapus file foto dari penyimpanan.
        Storage::disk('public')->delete($oldPhoto);

        return Redirect::route('profile.edit')
            ->with('status', 'Foto profil berhasil dihapus.');
    }

    /**
     * Menghapus akun pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => [
                'required',
                'current_password',
            ],
        ]);

        $user = $request->user();
        $profilePhoto = $user->profile_photo;

        Auth::logout();

        $user->delete();

        if ($profilePhoto) {
            Storage::disk('public')->delete($profilePhoto);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
