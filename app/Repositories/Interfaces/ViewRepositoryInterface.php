<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Cache\UserCacheRepository;
use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface ViewRepositoryInterface extends BaseRepositoryInterface
{
    public function latestOfAuthenticated(int $quantity = 1, UserCacheRepository $userRepository);
}
