<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Cache\UserCacheRepository;
use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getBySlug(string $slug, bool $withTrashed = false);

    public function search($search, $quantity, $sort_by, $filters);

    public function getOfUserPaginated($user_id, $quantity, $sort_by, $filters);

    public function getSimilarProducts($product_id, $quantity, $sort_by, $filters);

    public function getSellerProducts($user_id, $quantity, $sort_by, $filters);

    public function getProductsOfLatestSearch();

    public function getSimilarsOfLatestViewedProduct();

    public function getMostSearched();

    public function getMostViewed();

    public function getMostSold();

    public function getProductsOfSubcategorieWithMostSolds();

    public function getProductsOfSimilarUsers();
}
