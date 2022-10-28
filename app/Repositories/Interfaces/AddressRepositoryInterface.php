<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface AddressRepositoryInterface extends BaseRepositoryInterface
{
    public function paginatedUserAddresses(int $quantity, User $user);
}
