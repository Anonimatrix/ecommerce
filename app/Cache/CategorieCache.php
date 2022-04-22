<?php

namespace App\Cache;

use App\Repositories\CategorieRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class CategorieCache extends BaseCache implements CategorieRepositoryInterface
{
    protected $repository;

    public function __construct(CategorieRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'categorie');
        $this->repository = $repository;
    }

    public function allOrderByTitle()
    {
        return $this->cache->tags([$this->key])->remember($this->key . 's-ordered-by-title', self::TTL, function () {
            return $this->repository->allOrderByTitle();
        });
    }
}
