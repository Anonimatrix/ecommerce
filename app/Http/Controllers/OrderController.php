<?php

namespace App\Http\Controllers;

use App\Services\Billing\Contracts\PaymentGatewayInterface;
use App\Repositories\Cache\OrderCacheRepository;
use App\Repositories\Cache\PaymentCacheRepository;
use App\Repositories\Cache\ShippCacheRepository;
use App\Facades\AddressRepository;
use App\Facades\ProductRepository;
use App\Facades\ShippRepository;
use App\Http\Requests\PaidWebhookRequest;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Repositories\Cache\ProductCache;
use App\Repositories\Cache\UserCacheRepository;
use App\Statuses\OrderStatus;
use App\Services\Shipping\Contracts\ShippGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Services\Shipping\ShippingUtils;

class OrderController extends Controller
{
    protected $repository;
    protected $order;

    public function setOrder(Request $request)
    {
        $order_id = $request->route('order_id');

        if ($order_id) {
            $this->order = $this->repository->getById($order_id);
        }
    }

    public function __construct(OrderCacheRepository $orderCache, Request $request)
    {
        $this->repository = $orderCache;
        $this->setOrder($request);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function buys()
    {
        $pagination = $this->repository->buysForAuthenticated(15);

        return Inertia::render('Orders/Buys', compact('pagination'));
    }

    public function sells()
    {
        $pagination = $this->repository->sellsForAuthenticated(15);

        return Inertia::render('Orders/Sells', compact('pagination'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request, ShippGatewayInterface $shippGateway, PaymentGatewayInterface $paymentGateway)
    {
        extract(
            $request->only(
                ['product_id', 'address_id', 'quantity', 'shipp_type']
            )
        );

        $product = ProductRepository::getById($product_id);

        $order = $this->repository->create([
            'buyer_id' => Auth::id(),
            'address_id' => $address_id,
            'product_id' => $product_id,
            'status' => OrderStatus::PENDING,
            'quantity' => $quantity,
            'unit_price' => $product->price
        ]);

        $address = AddressRepository::getById($address_id);
        $shipp_res = ShippingUtils::isNeededPay($shipp_type) ? $shippGateway->quote($address->postal_code, $product, $shipp_type) : null;

        $shipp_price = $shipp_res === null || $shipp_res['status_code'] != 200 ? null : $shipp_res['price'];

        $shipp = ShippRepository::create([
            'order_id' => $order->id,
            'type' => $shipp_type,
            'price' => $shipp_price
        ]);

        $paymentUrl = $paymentGateway->getPaymentUrl($order, $shipp);

        return redirect()->to($paymentUrl);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(ShippGatewayInterface $shippGateway, PaymentGatewayInterface $paymentGateway, UserCacheRepository $userRepository)
    {
        $this->authorize('view', $this->order);

        $this->repository->load($this->order, 'product');

        if ($this->order->status === OrderStatus::PAYED && $this->order->shipp && $this->order->shipp->tracking_id) {
            $tracking_id = $this->order->shipp->tracking_id;
            $this->order->shipp_status = $shippGateway->getStatusShipp($tracking_id);
        }

        if ($this->order->status === OrderStatus::PENDING && $this->order->buyer->id == $userRepository->authenticated()->id) {
            $paymentUrl = $paymentGateway->getPaymentUrl($this->order, $this->order->shipp);

            return redirect()->to($paymentUrl);
        }

        return Inertia::render('Orders/Show', ['order' => $this->order]);
    }

    public function getLabel(ShippGatewayInterface $shippGateway)
    {
        if (!$this->order->shipp || !$this->order->shipp->tracking_id) {
            return response()->json(
                ['status' => 'failed', 'message' => 'order hasnt shipp'],
                404
            );
        }

        return response()->make(
            $shippGateway->getLabel($this->order->shipp->tracking_id),
            200,
            ['content-type' => 'application/pdf']
        );
    }

    public function paidWebhook(PaymentGatewayInterface $paymentGateway, PaidWebhookRequest $request, PaymentCacheRepository $paymentRepository, ProductCache $productRepository)
    {
        return $paymentGateway->paymentNotify($request->all(), $this->repository, $paymentRepository, $productRepository);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
