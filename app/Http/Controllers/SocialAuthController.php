<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $platform)
    {
        $driver = Socialite::driver($platform);

        if ($platform === 'google') {
            $driver->with(['access_type' => 'offline', 'prompt' => 'consent']);
        }

        return $driver->redirect();
    }

    public function callback(string $platform)
    {
        $socialUser = Socialite::driver($platform)->user();

        $user = User::updateOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name'     => $socialUser->getName() ?? $socialUser->getNickname(),
                'password' => bcrypt(str()->random(24)),
            ]
        );

        Auth::login($user);

        return redirect('/dashboard');
    }
}