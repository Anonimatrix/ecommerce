<?php

namespace App\Cache;

use App\Repositories\SubcategorieRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class SubcategorieCacheRepository extends BaseCache implements SubcategorieRepositoryInterface
{
    protected $repository;

    public function __construct(SubcategorieRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'subcategorie');
        $this->repository = $repository;
    }
}
