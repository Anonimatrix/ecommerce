<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\ViewRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Support\Facades\Auth;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class ViewCacheRepository extends BaseCache implements ViewRepositoryInterface
{
    protected $repository;

    public function __construct(ViewRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'view');
        $this->repository = $repository;
    }

    public function latestOfAuthenticated(int $quantity = 1, UserCacheRepository $userRepository)
    {
        $user = $userRepository->authenticated();

        return $this->cache->tags([$this->key])->remember($this->getRememberString(false, null, "latest-view-by-user-$user->id-$quantity"), self::TTL, function () use ($quantity, $userRepository) {
            return $this->repository->latestOfAuthenticated($quantity, $userRepository);
        });
    }
}
