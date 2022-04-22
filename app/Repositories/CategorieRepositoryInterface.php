<?php

namespace App\Repositories;

use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface CategorieRepositoryInterface extends BaseRepositoryInterface
{
    public function allOrderByTitle();
}
