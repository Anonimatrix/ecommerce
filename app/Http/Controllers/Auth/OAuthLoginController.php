<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OAuthSetPasswordRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class OAuthLoginController extends Controller
{
    public function redirectToProvider($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function handleProviderCallback($driver)
    {
        $userSocialite = Socialite::driver($driver)->user();

        $user = User::where('email', $userSocialite->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $userSocialite->getName(),
                'email' => $userSocialite->getEmail(),
                'username' => $userSocialite->getNickname() ?? $userSocialite->getName(),
                'email_verified_at' => Carbon::now(),
                'profile_photo_path' => $userSocialite->getAvatar()
            ]);
        }

        Auth::login($user);

        return redirect()->route('oauth.set-password-view');
    }

    public function setPasswordView()
    {
        return Inertia::render('Auth/SetPassword');
    }

    public function setPassword(OAuthSetPasswordRequest $request)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = Auth::user();

        $user->password = Hash::make($request->input('password'));

        $user->save();

        return redirect(RouteServiceProvider::HOME);
    }
}
