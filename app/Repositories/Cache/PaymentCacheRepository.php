<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class PaymentCacheRepository extends BaseCache implements PaymentRepositoryInterface
{
    protected $repository;

    public function __construct(PaymentRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'payment');
        $this->repository = $repository;
    }
}
