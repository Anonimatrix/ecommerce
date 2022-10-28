<?php

namespace App\Repositories\Cache;

use App\Models\User;
use App\Repositories\Interfaces\AddressRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class AddressCacheRepository extends BaseCache implements AddressRepositoryInterface
{
    protected $repository;

    public function __construct(AddressRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'address');
        $this->repository = $repository;
    }

    public function paginatedUserAddresses(int $quantity, User $user)
    {
        return $this->cache->tags([$this->key])->remember('user' . $this->key . 's', self::TTL, function () use ($quantity, $user) {
            return $this->repository->paginatedUserAddresses($quantity, $user);
        });
    }
}
