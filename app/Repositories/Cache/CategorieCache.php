<?php

namespace App\Repositories\Cache;

use App\Models\Categorie;
use App\Repositories\Interfaces\CategorieRepositoryInterface;
use App\Traits\Repositories\HasSortAndFilter;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class CategorieCache extends BaseCache implements CategorieRepositoryInterface
{
    use HasSortAndFilter;
    protected $repository;

    public function __construct(CategorieRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'categorie');
        $this->repository = $repository;
    }

    public function moveSubcategoriesToOtherCategorie(Categorie|int $categorie, Categorie|int $toCategorie)
    {
        $categorie_id = $this->checkInstanceOrId($categorie);
        $to_categorie_id = $this->checkInstanceOrId($toCategorie);

        $this->cache->tags(['subcategorie', $this->key . "-$categorie_id", $this->key . "-$to_categorie_id"])->flush();
        return $this->repository->moveSubcategoriesToOtherCategorie($categorie, $toCategorie);
    }

    public function removeSubcategoriesOfCategorie(Categorie|int $categorie)
    {
        $categorie_id = $this->checkInstanceOrId($categorie);

        $this->cache->tags(['subcategorie', $this->key . "-$categorie_id"])->flush();
        return $this->repository->removeSubcategoriesOfCategorie($categorie);
    }
}
