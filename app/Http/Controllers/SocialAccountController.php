<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use App\Services\Platforms\PlatformManager;
use Throwable;

class SocialAccountController extends Controller
{
    public function index()
    {
        $platforms = config('posting.platforms');
        $accounts = SocialAccount::where('user_id', auth()->id())->get()->keyBy('platform');
        return view('accounts.index', compact('platforms', 'accounts'));
    }

    public function refresh(string $platform, PlatformManager $manager)
    {
        $account = SocialAccount::where('user_id', auth()->id())->where('platform', $platform)->firstOrFail();
        try {
            $manager->for($platform)->refresh($account);
        } catch (Throwable $e) {
            return back()->withErrors(['akun' => 'Gagal menyegarkan token: '.$e->getMessage()]);
        }
        return back()->with('ok', 'Token berhasil disegarkan.');
    }

    public function destroy(string $platform)
    {
        SocialAccount::where('user_id', auth()->id())->where('platform', $platform)->delete();
        return back()->with('ok', 'Koneksi diputus dan token dihapus dari database.');
    }
}
