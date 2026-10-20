<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public const TTL_MINUTES  = 10;
    public const MAX_ATTEMPTS = 5;
    public const RESEND_DELAY = 60; // detik

    /**
     * Buat OTP baru dan kirim ke email.
     * Mengembalikan false jika permintaan terlalu cepat.
     */
    public function send(string $email, string $purpose): bool
    {
        $last = OtpCode::where('email', $email)
            ->where('purpose', $purpose)
            ->latest()
            ->first();

        if ($last && $last->created_at->diffInSeconds(now()) < self::RESEND_DELAY) {
            return false;
        }

        // hapus OTP lama yang belum terpakai
        OtpCode::where('email', $email)
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->delete();

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'email'      => $email,
            'purpose'    => $purpose,
            'code_hash'  => Hash::make($code),
            'expires_at' => now()->addMinutes(self::TTL_MINUTES),
        ]);

        Mail::to($email)->send(new OtpMail($code, $purpose, self::TTL_MINUTES));

        return true;
    }

    /**
     * Verifikasi OTP. Mengembalikan true jika benar.
     */
    public function verify(string $email, string $purpose, string $code): bool
    {
        $otp = OtpCode::where('email', $email)
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (! $otp || $otp->expires_at->isPast() || $otp->attempts >= self::MAX_ATTEMPTS) {
            return false;
        }

        if (! Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempts');
            return false;
        }

        $otp->update(['used_at' => now()]);

        return true;
    }
}