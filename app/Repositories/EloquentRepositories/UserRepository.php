<?php

namespace App\Repositories\EloquentRepositories;

use App\Repositories\Cache\OrderCacheRepository;
use App\Models\User;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

use function GuzzleHttp\Promise\each;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function authenticated(): User|null
    {
        return Auth::user();
    }

    public function login(User $user)
    {
        return Auth::login($user);
    }

    public function getByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function setMissingInfo(array $info)
    {
        $user = $this->authenticated();

        return $user->update($info);
    }
}
