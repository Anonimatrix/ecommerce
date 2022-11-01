<?php

namespace App\Repositories\Cache;

use App\Models\User;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Xkairo\CacheRepositoryLaravel\Cache\BaseCache;

class OrderCacheRepository extends BaseCache implements OrderRepositoryInterface
{
    protected $repository;

    public function __construct(OrderRepositoryInterface $repository, Cache $cache, Request $request)
    {
        parent::__construct($repository, $cache, $request, 'order');
        $this->repository = $repository;
    }

    //TODO use better tag to better cache
    public function buysForAuthenticated(int $quantity)
    {
        $user_id = Auth::id();

        return $this->cache->tags([$this->key/* . "s-buyer-id:$user_id"*/])->remember($this->getRememberString(true, $this->request->page, '-buys-for-authenticated'), self::TTL, function () use ($quantity) {
            return $this->repository->buysForAuthenticated($quantity);
        });
    }

    public function sellsForAuthenticated(int $quantity)
    {
        $user_id = Auth::id();

        return $this->cache->tags([$this->key/* . "s-buyer-id:$user_id"*/])->remember($this->getRememberString(true, $this->request->page, '-sells-for-authenticated'), self::TTL, function () use ($quantity) {
            return $this->repository->sellsForAuthenticated($quantity);
        });
    }

    public function getCompletedSellsForUser(User $user)
    {
        $user_id = $user->id;

        return $this->cache->tags([$this->key/* . "s-buyer-id:$user_id"*/])->remember($this->getRememberString(true, null, "-completed-orders|user-$user_id"), self::TTL, function () use ($user) {
            return $this->repository->getCompletedSellsForUser($user);
        });
    }

    public function getRefundedBuysForUser(User $user)
    {
        $user_id = $user->id;

        return $this->cache->tags([$this->key/* . "s-buyer-id:$user_id"*/])->remember($this->getRememberString(true, null, "-refunded-orders|user-$user_id"), self::TTL, function () use ($user) {
            return $this->repository->getRefundedBuysForUser($user);
        });
    }

    public function getPendingToChangeShippStatus()
    {
        return $this->repository->getPendingToChangeShippStatus();
    }
}
