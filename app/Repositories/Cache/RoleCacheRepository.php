<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class RoleCacheRepository extends BaseCache implements RoleRepositoryInterface
{
    protected $repository;

    public function __construct(RoleRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'role');
        $this->repository = $repository;
    }
}
