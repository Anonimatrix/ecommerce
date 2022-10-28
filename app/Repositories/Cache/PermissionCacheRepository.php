<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class PermissionCacheRepository extends BaseCache implements PermissionRepositoryInterface
{
    protected $repository;

    public function __construct(PermissionRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'permission');
        $this->repository = $repository;
    }
}
