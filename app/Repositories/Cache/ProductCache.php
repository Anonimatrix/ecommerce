<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Traits\Repositories\HasSortAndFilter;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Support\Facades\Auth;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class ProductCache extends BaseCache implements ProductRepositoryInterface
{

    use HasSortAndFilter;

    protected $repository;
    protected $request;
    protected $userRepository;

    public function __construct(ProductRepositoryInterface $repository, Cache $cache, Request $request, UserCacheRepository $userRepository)
    {
        parent::__construct($repository, $cache, $request, 'product');
        $this->repository = $repository;
        $this->request = $request;
        $this->userRepository = $userRepository;
    }

    public function getBySlug(string $slug, bool $withTrashed = false)
    {
        return $this->cache->tags([$this->key])->remember($this->getRememberString(false, null, "-$slug-get-by-slug", null, [], $withTrashed), self::TTL, function () use ($slug, $withTrashed) {
            return $this->repository->getBySlug($slug, $withTrashed);
        });
    }

    public function search($search, $quantity, $sort_by, $filters)
    {
        $page = $this->request->page;

        return $this->cache->tags([$this->key])->remember($this->getRememberString(true, $page, "-search-$search", $sort_by, $filters), self::TTL, function () use ($search, $quantity, $sort_by, $filters) {
            return $this->repository->search($search, $quantity, $sort_by, $filters);
        });
    }

    public function getOfUserPaginated($user_id, $quantity, $sort_by, $filters)
    {
        $page = $this->request->input('page');

        return $this->cache->tags([$this->key])->remember($this->getRememberString(true, $page, "-user.$user_id", $sort_by, $filters), self::TTL, function () use ($user_id, $quantity, $sort_by, $filters) {
            return $this->repository->getOfUserPaginated($user_id, $quantity, $sort_by, $filters);
        });
    }

    public function getSimilarProducts($product_id, $quantity, $sort_by, $filters)
    {
        $page = $this->request->input('page');

        return $this->cache->tags([$this->key])->remember($this->getRememberString(true, $page, "-product_id.$product_id", $sort_by, $filters), self::TTL, function () use ($product_id, $quantity, $sort_by, $filters) {
            return $this->repository->getSimilarProducts($product_id, $quantity, $sort_by, $filters);
        });
    }

    //TODO use different tag to use better cache
    public function getSellerProducts($user_id, $quantity, $sort_by, $filters)
    {
        $page = $this->request->input('page');

        return $this->cache->tags([$this->key])->remember($this->getRememberString(true, $page, "-user.$user_id", $sort_by, $filters), self::TTL, function () use ($user_id, $quantity, $sort_by, $filters) {
            return $this->repository->getSellerProducts($user_id, $quantity, $sort_by, $filters);
        });
    }

    public function getProductsOfLatestSearch()
    {
        $user = $this->userRepository->authenticated();

        return $this->cache->tags([$this->key])->remember($this->getRememberString(true, null, "-latest-search-user.$user->id"), self::TTL, function () {
            return $this->repository->getProductsOfLatestSearch();
        });
    }

    public function getSimilarsOfLatestViewedProduct()
    {
        $user = $this->userRepository->authenticated();

        return $this->cache->tags([$this->key])->remember($this->getRememberString(true, null, "-similar-latest-view-user.$user->id"), self::TTL, function () {
            return $this->repository->getSimilarsOfLatestViewedProduct();
        });
    }

    public function getMostSearched()
    {
        return $this->cache->tags([$this->key])->remember($this->getRememberString(true, null, "-most-searched"), self::TTL, function () {
            return $this->repository->getMostSearched();
        });
    }

    public function getMostSold()
    {
        return $this->cache->tags([$this->key])->remember($this->getRememberString(true, null, "-most-sold"), self::TTL, function () {
            return $this->repository->getMostSold();
        });
    }

    public function getProductsOfSubcategorieWithMostSolds()
    {
        return $this->cache->tags([$this->key])->remember($this->getRememberString(true, null, "-subcategorie-with-most-solds"), self::TTL, function () {
            return $this->repository->getProductsOfSubcategorieWithMostSolds();
        });
    }

    public function getProductsOfSimilarUsers()
    {
        $user = $this->userRepository->authenticated();

        return $this->cache->tags([$this->key])->remember($this->getRememberString(true, null, "-similar-to-user-$user->id"), self::TTL, function () {
            return $this->repository->getProductsOfSimilarUsers();
        });
    }

    public function getMostViewed()
    {
        return $this->cache->tags([$this->key])->remember($this->getRememberString(true, null, "-most-viewed"), self::TTL, function () {
            return $this->repository->getMostViewed();
        });
    }
}
