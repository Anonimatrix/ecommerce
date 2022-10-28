<?php

namespace App\Services\Billing\Contracts;

use App\Models\Order;
use App\Models\Product;
use App\Models\Shipp;
use App\Repositories\Cache\OrderCacheRepository;
use App\Repositories\Cache\PaymentCacheRepository;
use App\Repositories\Cache\ProductCache;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;

interface PaymentGatewayInterface
{
    public function getPaymentUrl(Order $order, Shipp $shipp);

    public function paymentNotify(array $notificationData, OrderCacheRepository $orderRepository, PaymentCacheRepository $paymentRepository, ProductCache $productRepository);

    public function withdraw();
}
