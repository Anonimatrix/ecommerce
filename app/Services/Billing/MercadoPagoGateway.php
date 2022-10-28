<?php

namespace App\Services\Billing;

use App\Exceptions\Payment\UnavailableServiceException;
use App\Services\Billing\Contracts\PaymentGatewayInterface as ContractsPaymentGatewayInterface;
use App\Facades\PaymentRepository;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipp;
use App\Repositories\Cache\OrderCacheRepository;
use App\Repositories\Cache\PaymentCacheRepository;
use App\Repositories\Cache\ProductCache;
use App\Statuses\OrderStatus;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Billing\Contracts\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use MercadoPago\Config\Json;
use MercadoPago\Item;
use MercadoPago\MerchantOrder;
use MercadoPago\OAuth;
use MercadoPago\Payer;
use MercadoPago\Payment;
use MercadoPago\PaymentMethod;
use MercadoPago\Preference;
use MercadoPago\SDK;
use Throwable;

class MercadoPagoGateway implements ContractsPaymentGatewayInterface
{
    public function __construct()
    {
        $access_token = App::environment('production')
            ? env('MP_ACCESS_TOKEN')
            : env('MP_SANDBOX_ACCESS_TOKEN');

        SDK::setAccessToken($access_token);
    }

    public function withdraw()
    {
        $payer = new Payer();

        $payer->email = "tavelliezequiel@gmail.com";

        $payment = new Payment();

        $payment->payer = $payer;

        $payment->transaction_amount = 10;

        $payment->payment_method_id = "pagofacil";

        $payment->installments = 0;

        $payment->save();

        // dd(PaymentMethod::all());
    }

    public function getPaymentUrl(Order $order, Shipp $shipp)
    {
        $preference = new Preference();

        $item = new Item();

        $product = $order->product;
        $quantity = $order->quantity;

        $item->title = $product->title;
        $item->quantity = $quantity;
        $item->unit_price = $product->price;

        $items = array($item);

        $shipp_item = new Item();

        if ($shipp->price && $shipp->price > 0) {
            $shipp_item->title = "Envio";
            $shipp_item->quantity = 1;
            $shipp_item->unit_price = $shipp->price;
            array_push($items, $shipp_item);
        }

        $preference->items = $items;

        $preference->additional_info = json_encode(['order_id' => $order->id]);

        $saved = $preference->save();

        if (!$saved) {
            throw new UnavailableServiceException();
        }

        return App::environment('production')
            ? $preference->init_point
            : $preference->sandbox_init_point;
    }

    public function paymentNotify(array $notificationData, OrderCacheRepository $orderRepository, PaymentCacheRepository $paymentRepository, ProductCache $productRepository)
    {
        $MpPaymentId = $notificationData['data']['id'];
        $MpPayment = Payment::find_by_id($MpPaymentId);

        if (is_null($MpPayment) || $MpPayment->status !== 'approved') {
            return response()->json(['status' => 'failed', 'message' => 'invalid payment']);
        }

        $MpOrderId = $MpPayment->order_id;
        $MpOrder = MerchantOrder::find_by_id($MpOrderId);

        if (is_null($MpOrder) || is_null($MpOrder->preferenceId)) {
            return response()->json(['status' => 'failed', 'message' => 'invalid mp order']);
        }

        $MpPreference = Preference::find_by_id($MpOrder->preferenceId);

        if (is_null($MpPreference)) {
            return response()->json(['status' => 'failed', 'message' => 'invalid preference']);
        }

        $MpPreferenceAdditionalInfo = $MpPreference->additional_info ? json_decode($MpPreference->additional_info) : [];

        $orderId = $MpPreferenceAdditionalInfo->order_id;
        $order = $orderRepository->getById($orderId);

        if (is_null($order)) {
            return response()->json(['status' => 'failed', 'message' => 'invalid order']);
        }

        $orderRepository->update(['status' => OrderStatus::PAYED], $order);

        $productRepository->update(['stock' => $order->product->stock - 1], $order->product);

        $paymentRepository->create([
            'order_id' => $orderId,
            'type' => 'MP',
            'amount' => $order->total_price
        ]);

        return response()->json(['status' => 'completed', 'message' => 'payed successfully'], 200);
    }
}
