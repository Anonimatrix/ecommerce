<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Subcategorie;
use App\Repositories\SubcategorieRepositoryInterface;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class SubcategorieRepository extends BaseRepository implements SubcategorieRepositoryInterface
{

    protected $relations = [
        'products'
    ];

    public function __construct(Subcategorie $subcategorie)
    {
        parent::__construct($subcategorie);
    }

    public function paginatedProductsOfSubcategorie(int $paginate, Subcategorie $subcategorie)
    {
        return $subcategorie->products()->paginate($paginate);
    }
}
