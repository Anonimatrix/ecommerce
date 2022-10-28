<?php

namespace App\Repositories\Interfaces;

use App\Models\Categorie;
use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface CategorieRepositoryInterface extends BaseRepositoryInterface
{
    public function moveSubcategoriesToOtherCategorie(Categorie|int $categorie, Categorie|int $toCategorie);

    public function removeSubcategoriesOfCategorie(Categorie|int $categorie);
}
