<?php

namespace App\Cache;

use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class ProductCache extends BaseCache implements ProductRepositoryInterface
{
    protected $repository;

    public function __construct(ProductRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'product');
        $this->repository = $repository;
    }

    public function search($search)
    {
        return $this->cache->tags([$this->key . 's'])->remember($this->key . "s-search-$search", self::TTL, function () use ($search) {
            return $this->repository->search($search);
        });
    }
}
