<?php

namespace App\Repositories;

use App\Models\Subcategorie;
use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface SubcategorieRepositoryInterface extends BaseRepositoryInterface
{
    public function paginatedProductsOfSubcategorie(int $paginate, Subcategorie $subcategorie);
}
