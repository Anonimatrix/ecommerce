<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Categorie;
use App\Repositories\CategorieRepositoryInterface;
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

    public function allOrderByTitle()
    {
        return $this->model->with($this->relations)->orderBy('title')->get();
    }
}
