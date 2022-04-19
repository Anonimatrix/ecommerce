<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Product;
use App\Repositories\TagRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }
}
