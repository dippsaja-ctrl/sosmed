<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        $social = Socialite::driver($provider)->user();

        $user = User::updateOrCreate(
            ['provider_name' => $provider, 'provider_id' => $social->getId()],
            [
                'name'     => $social->getName() ?? $social->getNickname() ?? 'Pengguna',
                'email'    => $social->getEmail() ?? $social->getId().'@'.$provider.'.local',
                'password' => bcrypt(Str::random(32)),
            ]
        );

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }
}
