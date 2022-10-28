<?php

namespace App\Repositories\Cache;

use App\Models\Subcategorie;
use App\Repositories\Interfaces\SubcategorieRepositoryInterface;
use App\Traits\Repositories\HasSortAndFilter;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class SubcategorieCacheRepository extends BaseCache implements SubcategorieRepositoryInterface
{
    protected $repository;
    use HasSortAndFilter;

    public function __construct(SubcategorieRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'subcategorie');
        $this->repository = $repository;
    }

    public function paginatedProductsOfSubcategorie(int $paginate, Subcategorie $subcategorie, $sort_by = ['title', 'ASC'], $filters = [])
    {
        return $this->cache->tags([$this->key])->remember($this->getRememberString(true, $this->request->page, '-paginated-products-of-subcategorie', $sort_by, $filters), self::TTL, function () use ($paginate, $subcategorie, $sort_by, $filters) {
            return $this->repository->paginatedProductsOfSubcategorie($paginate, $subcategorie, $sort_by, $filters);
        });
    }

    public function withMostProductsSold()
    {
        return $this->cache->tags([$this->key])->remember($this->getRememberString(true, null, '-most-selled'), self::TTL, function () {
            return $this->repository->withMostProductsSold();
        });
    }
}
