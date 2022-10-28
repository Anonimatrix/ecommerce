<?php

namespace App\Repositories\Cache;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Support\Facades\Auth;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class UserCacheRepository extends BaseCache implements UserRepositoryInterface
{
    protected $repository;

    public function __construct(UserRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'user');
        $this->repository = $repository;
    }

    public function authenticated(): User|null
    {
        return $this->repository->authenticated();
    }

    public function login(User $user)
    {
        return $this->repository->login($user);
    }

    public function getByEmail(string $email)
    {
        return $this->cache->tags([$this->key, $this->key . '-' . $email])->remember($this->getRememberString(false, null, "email-$email"), self::TTL, function () use ($email) {
            return $this->repository->getByEmail($email);
        });
    }

    public function setMissingInfo(array $info)
    {
        $this->cache->tags([$this->key])->flush();
        return $this->repository->setMissingInfo($info);
    }
}
