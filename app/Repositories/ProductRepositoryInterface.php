<?php

namespace App\Repositories;

use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function search($search);
}
