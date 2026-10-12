<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TikTokAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class TikTokAuthController extends Controller
{
    private const AUTHORIZE_URL = 'https://www.tiktok.com/v2/auth/authorize/';
    private const TOKEN_URL     = 'https://open.tiktokapis.com/v2/oauth/token/';
    private const USER_INFO_URL = 'https://open.tiktokapis.com/v2/user/info/';
    private const SCOPES        = 'user.info.basic';
    private const STATE_KEY     = 'tiktok_oauth_state';

    /** Langkah 1: arahkan user ke halaman otorisasi TikTok. */
    public function redirect(Request $request): RedirectResponse
    {
        if (! $this->isConfigured()) {
            Log::error('TikTok OAuth: konfigurasi services.tiktok belum lengkap.');
            return redirect('/')->with('error', 'Konfigurasi TikTok belum lengkap.');
        }

        $state = Str::random(40);
        $request->session()->put(self::STATE_KEY, $state);

        $query = http_build_query([
            'client_key'    => config('services.tiktok.client_id'), // Client KEY dari portal TikTok
            'response_type' => 'code',
            'scope'         => self::SCOPES,
            'redirect_uri'  => config('services.tiktok.redirect'),
            'state'         => $state,
        ], '', '&', PHP_QUERY_RFC3986);

        return redirect()->away(self::AUTHORIZE_URL . '?' . $query);
    }

    /** Langkah 2: TikTok mengembalikan user ke sini membawa code + state. */
    public function callback(Request $request): RedirectResponse
    {
        // Validasi state (anti-CSRF). pull() = ambil sekaligus hapus (sekali pakai).
        $expected = $request->session()->pull(self::STATE_KEY);
        $received = (string) $request->query('state', '');

        if (! is_string($expected) || $expected === '' || ! hash_equals($expected, $received)) {
            return $this->fail('Sesi login TikTok tidak valid atau kedaluwarsa. Silakan coba lagi.');
        }

        // User menolak atau TikTok mengembalikan error.
        if ($request->filled('error')) {
            Log::warning('TikTok OAuth ditolak/gagal', [
                'error'       => $request->query('error'),
                'description' => $request->query('error_description'),
            ]);
            return $this->fail('Otorisasi TikTok dibatalkan atau gagal.');
        }

        $code = (string) $request->query('code', '');
        if ($code === '') {
            return $this->fail('Kode otorisasi dari TikTok tidak ditemukan.');
        }

        // Tukar code -> access token.
        try {
            $response = Http::asForm()
                ->acceptJson()
                ->withHeaders(['Cache-Control' => 'no-cache'])
                ->timeout(20)
                ->post(self::TOKEN_URL, [
                    'client_key'    => config('services.tiktok.client_id'),
                    'client_secret' => config('services.tiktok.client_secret'),
                    'code'          => $code,
                    'grant_type'    => 'authorization_code',
                    'redirect_uri'  => config('services.tiktok.redirect'),
                ]);
        } catch (Throwable $e) {
            Log::error('TikTok OAuth: request token gagal', ['message' => $e->getMessage()]);
            return $this->fail('Tidak dapat menghubungi TikTok. Silakan coba lagi.');
        }

        $data = $response->json() ?? [];

        if (! $response->successful() || empty($data['access_token']) || empty($data['open_id'])) {
            // Jangan log body penuh agar token/secret tidak bocor.
            Log::error('TikTok OAuth: penukaran token gagal', [
                'status'      => $response->status(),
                'error'       => $data['error'] ?? null,
                'description' => $data['error_description'] ?? null,
            ]);
            return $this->fail('Gagal menukar kode otorisasi dengan token TikTok.');
        }

        $profile = $this->fetchProfile($data['access_token']);

        TikTokAccount::updateOrCreate(
            ['open_id' => $data['open_id']],
            [
                'user_id'                  => Auth::id(),
                'display_name'             => $profile['display_name'] ?? null,
                'avatar_url'               => $profile['avatar_url'] ?? null,
                'access_token'             => $data['access_token'],
                'refresh_token'            => $data['refresh_token'] ?? null,
                'access_token_expires_at'  => now()->addSeconds((int) ($data['expires_in'] ?? 0)),
                'refresh_token_expires_at' => isset($data['refresh_expires_in'])
                    ? now()->addSeconds((int) $data['refresh_expires_in'])
                    : null,
                'scopes'                   => $data['scope'] ?? null,
            ]
        );

        return redirect('/')->with('status', 'Akun TikTok berhasil terhubung.');
    }

    /** Ambil profil dasar (opsional; kegagalan di sini tidak membatalkan login). */
    private function fetchProfile(string $accessToken): array
    {
        try {
            $res = Http::withToken($accessToken)
                ->acceptJson()
                ->timeout(15)
                ->get(self::USER_INFO_URL, ['fields' => 'open_id,display_name,avatar_url']);

            return $res->successful() ? (array) data_get($res->json(), 'data.user', []) : [];
        } catch (Throwable $e) {
            return [];
        }
    }

    private function isConfigured(): bool
    {
        return filled(config('services.tiktok.client_id'))
            && filled(config('services.tiktok.client_secret'))
            && filled(config('services.tiktok.redirect'));
    }

    private function fail(string $message): RedirectResponse
    {
        return redirect('/')->with('error', $message);
    }
}