<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class SocialAccountController extends Controller
{
    public function redirect(string $platform)
    {
        return Socialite::driver($platform)->redirect();
    }

    public function callback(string $platform): RedirectResponse
    {
        $token = Socialite::driver($platform)->user();

        SocialAccount::updateOrCreate(
            ['user_id' => auth()->id(), 'platform' => $platform],
            [
                'access_token'  => $token->token,
                'refresh_token' => $token->refreshToken,
                'expires_at'    => $token->expiresIn
                    ? now()->addSeconds($token->expiresIn)
                    : null,
            ]
        );

        return redirect()->route('dashboard')->with('success', 'Akun terhubung.');
    }
}