<?php

namespace App\Services;

use App\Models\SocialAccount;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialTokenService
{
    /**
     * Perbarui access_token memakai refresh_token.
     */
    public function refresh(SocialAccount $account): bool
    {
        if (! $account->refresh_token) {
            return false;
        }

        try {
            $token = Socialite::driver($account->provider)
                ->refreshToken($account->refresh_token);
        } catch (ClientException $e) {
            // 4xx dari provider (mis. invalid_grant): refresh_token dicabut/kedaluwarsa
            Log::warning("Refresh token ditolak [{$account->provider}] account #{$account->id}", [
                'status' => $e->getResponse()->getStatusCode(),
            ]);

            $account->update(['needs_reauth' => true]);

            return false;
        } catch (Throwable $e) {
            // Masalah sementara (jaringan, 5xx): jangan tandai, coba lagi di jadwal berikutnya
            report($e);

            return false;
        }

        $account->update([
            'access_token'     => $token->token,
            // Sebagian provider tidak mengirim refresh_token baru, pertahankan yang lama
            'refresh_token'    => $token->refreshToken ?: $account->refresh_token,
            'token_expires_at' => $token->expiresIn
                ? now()->addSeconds($token->expiresIn)
                : null,
            'needs_reauth'     => false,
        ]);

        return true;
    }

    /**
     * Ambil access_token yang pasti valid (refresh dulu kalau perlu).
     * Pakai ini setiap kali mau memanggil API sosmed atas nama user.
     */
    public function getValidToken(SocialAccount $account): ?string
    {
        $expiring = $account->token_expires_at
            && $account->token_expires_at->lte(now()->addMinutes(2));

        if ($expiring) {
            if (! $this->refresh($account)) {
                return null;
            }

            $account->refresh();
        }

        return $account->access_token;
    }
}