<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    public function search($search)
    {
        return $this->model->where(DB::raw('LOWER(title)'), 'LIKE', "%$search%")->orWhereHas(
            'tags',
            fn ($query) =>
            $query->where('title', 'LIKE', "%$search%")
        )->get();
    }
}
