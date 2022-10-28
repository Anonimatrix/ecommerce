<?php

namespace App\Observers;

use App\Facades\AddressRepository;
use App\Facades\ChatRepository;
use App\Facades\ShippGateway;
use App\Facades\ShippRepository;
use App\Models\Order;
use App\Repositories\Cache\AddressCacheRepository;
use App\Repositories\Cache\ShippCacheRepository;
use App\Statuses\OrderStatus;
use App\Services\Shipping\Contracts\ShippGatewayInterface;
use Illuminate\Support\Facades\Config;
use App\Services\Shipping\ShippingUtils;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        if ($order->status === OrderStatus::PAYED) {
            ChatRepository::createIfNotExists(['chateable_id' => $order->id, 'chateable_type' => Order::class]);
            $shipp = $order->shipp;

            if ($shipp && ShippingUtils::isNeededPay($order->shipp->type)) {
                ShippGateway::createShipment($order, $shipp->type);
            }
        }
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
