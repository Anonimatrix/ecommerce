<?php

namespace App\Cache;

use App\Models\Subcategorie;
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

    public function paginatedProductsOfSubcategorie(int $paginate, Subcategorie $subcategorie)
    {
        return $this->cache->tags([$this->key . 's'])->remember($this->key . 's-paginated-products-of-subcategorie', self::TTL, function () use ($paginate, $subcategorie) {
            return $this->repository->paginatedProductsOfSubcategorie($paginate, $subcategorie);
        });
    }
}
