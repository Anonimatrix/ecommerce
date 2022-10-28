<?php

namespace App\Repositories\Interfaces;

use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface ChatRepositoryInterface extends BaseRepositoryInterface
{
    public function createIfNotExists(array $data);
}
