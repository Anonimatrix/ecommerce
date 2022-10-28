<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\ComplaintRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class ComplaintCacheRepository extends BaseCache implements ComplaintRepositoryInterface
{
    protected $repository;

    public function __construct(ComplaintRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'complaint');
        $this->repository = $repository;
    }
}
