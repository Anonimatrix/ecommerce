<?php

namespace App\Cache;

use App\Repositories\AdressRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class AdressCache extends BaseCache implements AdressRepositoryInterface
{
    protected $repository;

    public function __construct(AdressRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'adress');
        $this->repository = $repository;
    }
}
