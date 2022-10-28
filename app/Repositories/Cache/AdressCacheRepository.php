<?php

namespace App\Repositories\Cache;

use App\Models\User;
use App\Repositories\Interfaces\AdressRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class AdressCacheRepository extends BaseCache implements AdressRepositoryInterface
{
    protected $repository;

    public function __construct(AdressRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'adress');
        $this->repository = $repository;
    }

    public function paginatedUserAdresses(int $quantity, User $user)
    {
        return $this->cache->tags([$this->key])->remember('user' . $this->key . 's', self::TTL, function () use ($quantity, $user) {
            return $this->repository->paginatedUserAdresses($quantity, $user);
        });
    }
}
