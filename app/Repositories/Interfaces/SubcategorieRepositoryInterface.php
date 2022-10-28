<?php

namespace App\Repositories\Interfaces;

use App\Models\Subcategorie;
use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface SubcategorieRepositoryInterface extends BaseRepositoryInterface
{
    public function paginatedProductsOfSubcategorie(int $paginate, Subcategorie $subcategorie, $sort_by, $filters);

    public function withMostProductsSold();
}
