<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface AdressRepositoryInterface extends BaseRepositoryInterface
{
    public function paginatedUserAdresses(int $quantity, User $user);
}
