<?php

namespace App\Repositories\Interfaces;

use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface TagRepositoryInterface extends BaseRepositoryInterface
{
    public function createMany(array $titles);

    public function suggest(string $search, int $limit);
}
