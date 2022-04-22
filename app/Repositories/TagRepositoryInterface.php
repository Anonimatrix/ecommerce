<?php

namespace App\Repositories;

use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface TagRepositoryInterface extends BaseRepositoryInterface
{
    public function createMany(array $titles);
}
