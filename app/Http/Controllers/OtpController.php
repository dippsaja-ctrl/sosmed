<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OtpController extends Controller
{
    public function __construct(private OtpService $otp) {}

    // Kirim OTP (registrasi / lupa password)
    public function send(Request $request)
    {
        $data = $request->validate([
            'email'   => ['required', 'email'],
            'purpose' => ['required', 'in:register,reset_password'],
        ]);

        // Untuk reset password, jangan bocorkan apakah email terdaftar
        if ($data['purpose'] === 'reset_password'
            && ! User::where('email', $data['email'])->exists()) {
            return response()->json(['message' => 'Jika email terdaftar, kode telah dikirim.']);
        }

        if (! $this->otp->send($data['email'], $data['purpose'])) {
            return response()->json(['message' => 'Tunggu sebentar sebelum meminta kode lagi.'], 429);
        }

        return response()->json(['message' => 'Jika email terdaftar, kode telah dikirim.']);
    }

    // Verifikasi email saat registrasi
    public function verifyRegister(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'code'  => ['required', 'digits:6'],
        ]);

        if (! $this->otp->verify($data['email'], 'register', $data['code'])) {
            return response()->json(['message' => 'Kode salah atau kedaluwarsa.'], 422);
        }

        User::where('email', $data['email'])->update(['email_verified_at' => now()]);

        return response()->json(['message' => 'Email berhasil diverifikasi.']);
    }

    // Reset password dengan OTP
    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'code'     => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! $this->otp->verify($data['email'], 'reset_password', $data['code'])) {
            return response()->json(['message' => 'Kode salah atau kedaluwarsa.'], 422);
        }

        User::where('email', $data['email'])->update([
            'password' => Hash::make($data['password']),
        ]);

        return response()->json(['message' => 'Password berhasil diubah.']);
    }
}