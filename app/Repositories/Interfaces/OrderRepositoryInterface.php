<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function buysForAuthenticated(int $quantity);

    public function sellsForAuthenticated(int $quantity);

    public function getCompletedSellsForUser(User $user);

    public function getRefundedBuysForUser(User $user);
}
