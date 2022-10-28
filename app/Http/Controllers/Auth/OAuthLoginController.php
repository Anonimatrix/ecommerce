<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\Cache\UserCacheRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\OAuthSetPasswordRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class OAuthLoginController extends Controller
{
    protected $repository;

    public function __construct(UserCacheRepository $userCache, Request $request)
    {
        $this->repository = $userCache;
    }

    public function redirectToProvider($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function handleProviderCallback($driver)
    {
        $userSocialite = Socialite::driver($driver)->user();

        $user = $this->repository->getByEmail($userSocialite->getEmail());

        if (!$user) {
            $first_name_key = $driver == 'google' ? 'given_name' : 'first_name';
            $last_name_key = $driver == 'google' ? 'family_name' : 'last_name';

            $user = $this->repository->create([
                'name' => $userSocialite[$first_name_key],
                'last_name' => $userSocialite[$last_name_key],
                'email' => $userSocialite->getEmail(),
                'username' => $userSocialite->getNickname() ?? $userSocialite->getName(),
                'email_verified_at' => Carbon::now(),
                'profile_photo_path' => $userSocialite->getAvatar()
            ]);
        }

        $this->repository->login($user);

        return redirect()->route('auth.set-info-view');
    }

    public function setInfoView()
    {
        $dni_types = Config::get('user.data.dni_types');

        return Inertia::render('Auth/SetPassword', compact('dni_types'));
    }

    public function setInfo(OAuthSetPasswordRequest $request)
    {
        $info = $request->only('password', 'dni_type', 'dni_number');

        $this->repository->setMissingInfo($info);

        return redirect(RouteServiceProvider::HOME);
    }
}
