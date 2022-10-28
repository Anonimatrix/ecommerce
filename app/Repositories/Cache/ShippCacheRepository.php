<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\ShippRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class ShippCacheRepository extends BaseCache implements ShippRepositoryInterface
{
    protected $repository;

    public function __construct(ShippRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'shipp');
        $this->repository = $repository;
    }
}
