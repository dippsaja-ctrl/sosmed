<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use App\Services\Platforms\PlatformManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class OAuthController extends Controller
{
    public function __construct(private PlatformManager $platforms) {}

    /** Langkah 1: kirim pengguna ke halaman login/izin resmi platform. */
    public function redirect(string $platform)
    {
        $client = $this->platforms->for($platform);
        $state = Str::random(40);
        session(['oauth_state' => $state, 'oauth_platform' => $platform]);

        return redirect()->away($client->authUrl($state));
    }

    /** Langkah 2: platform memanggil balik alamat ini dengan ?code=...&state=... */
    public function callback(Request $request, string $platform)
    {
        $back = redirect()->route('accounts.index');

        if ($request->filled('error')) {
            return $back->withErrors(['akun' => 'Izin ditolak atau dibatalkan: '.$request->input('error_description', $request->input('error'))]);
        }

        // Cegah CSRF: state harus sama dengan yang kita simpan di sesi.
        $expected = (string) session()->pull('oauth_state', '');
        if ($expected === '' || ! hash_equals($expected, (string) $request->input('state'))
            || session()->pull('oauth_platform') !== $platform) {
            return $back->withErrors(['akun' => 'Sesi OAuth tidak valid. Coba hubungkan lagi.']);
        }

        try {
            $data = $this->platforms->for($platform)->exchangeCode((string) $request->input('code'));
        } catch (Throwable $e) {
            report($e);
            return $back->withErrors(['akun' => 'Gagal menghubungkan: '.$e->getMessage()]);
        }

        SocialAccount::updateOrCreate(
            ['user_id' => auth()->id(), 'platform' => $platform],
            $data + ['connected_at' => now()]
        );

        return $back->with('ok', config("posting.platforms.$platform.label").' berhasil dihubungkan.');
    }
}
