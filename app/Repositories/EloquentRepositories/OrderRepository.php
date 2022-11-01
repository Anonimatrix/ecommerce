<?php

namespace App\Repositories\EloquentRepositories;

use App\Repositories\Cache\UserCacheRepository;
use App\Models\Order;
use App\Models\User;
use App\Statuses\OrderStatus;
use App\Statuses\PaymentStatus;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected $userRepository;

    public function __construct(Order $order, UserCacheRepository $userRepository)
    {
        parent::__construct($order);
        $this->userRepository = $userRepository;
    }

    public function buysForAuthenticated(int $quantity)
    {
        $user = $this->userRepository->authenticated();

        $this->userRepository->load($user, 'orders');

        $orders = $user->orders()->paginate(15);

        return $orders;
    }

    public function sellsForAuthenticated(int $quantity)
    {
        $user = $this->userRepository->authenticated();

        $products = $user->products;

        $products_ids = $products->pluck('id')->all();

        $orders = Order::whereIn('product_id', $products_ids)->paginate($quantity);

        return $orders;
    }

    public function getCompletedSellsForUser(User $user)
    {
        $products = $user->products;

        $products_ids = $products->pluck('id')->all();

        $orders = Order::with('payment')
            ->whereIn('product_id', $products_ids)
            ->where('status', OrderStatus::COMPLETED)
            ->whereHas(
                'payment',
                fn ($query) => $query->where('status', PaymentStatus::COMPLETED)
                    ->whereNull('withdrawed_at')
            )
            ->get();

        return $orders;
    }

    public function getRefundedBuysForUser(User $user)
    {
        $orders = $user->orders()->whereHas(
            'payment',
            fn ($query) => $query->where('status', PaymentStatus::REFUNDED)
                ->whereNull('withdrawed_at')
        )->get();

        return $orders;
    }

    public function getPendingToChangeShippStatus()
    {
        $orders = $this->model
            ->where('status', OrderStatus::PAYED)
            ->orWhere('status', OrderStatus::SHIPPED)
            ->orWhereHas('shipp', fn ($query) => $query->whereNotNull('tracking_id'))
            ->get();

        return $orders;
    }
}
