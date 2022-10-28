<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Categorie;
use App\Repositories\Interfaces\CategorieRepositoryInterface;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class CategorieRepository extends BaseRepository implements CategorieRepositoryInterface
{
    public $relations = [
        'subcategories'
    ];

    public function __construct(Categorie $categorie)
    {
        parent::__construct($categorie);
    }

    public function moveSubcategoriesToOtherCategorie(Categorie|int $categorie, Categorie|int $toCategorie)
    {

        $instanceCategorie = $this->checkInstanceOrId($categorie);

        $instanceToCategorie = $this->checkInstanceOrId($toCategorie);

        return $instanceCategorie->subcategories()->update(['categorie_id' => $instanceToCategorie->id]);
    }

    public function removeSubcategoriesOfCategorie(Categorie|int $categorie)
    {
        $instanceCategorie = $this->checkInstanceOrId($categorie);

        return $instanceCategorie->subcategories()->delete();
    }
}
