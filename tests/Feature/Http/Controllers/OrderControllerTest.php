<?php

namespace Tests\Feature\Http\Controllers;

use App\Exceptions\Payment\UnavailableServiceException;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipp;
use App\Models\User;
use App\Statuses\OrderStatus;
use App\Services\Shipping\Contracts\ShippGatewayInterface;
use App\Services\Shipping\ShippTypes;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Mockery;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_authorization()
    {
        $order = Order::factory()->create();

        $this->post(route('orders.store'), [])
            ->assertRedirect(route('login'));
        $this->get(route('orders.show', $order->id))
            ->assertRedirect(route('login'));
    }
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_store()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $address = Address::factory()->create();

        $product = Product::factory()->create();

        $data = [
            'address_id' => $address->id,
            'product_id' => $product->id,
            'quantity' => $product->stock,
            'shipp_type' => ShippTypes::ACCORD_WITH_SELLER
        ];

        $this->actingAs($user)->post(route('orders.store'), $data)
            ->assertSessionHasNoErrors()
            ->assertRedirectContains('mercadopago');

        $this->assertDatabaseHas('orders', ['address_id' => $data['address_id'], 'product_id' => $data['product_id']]);
    }

    public function test_store_with_pay_shipment()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $address = Address::factory()->create();

        $product = Product::factory()->create();

        $data = [
            'address_id' => $address->id,
            'product_id' => $product->id,
            'quantity' => $product->stock,
            'shipp_type' => ShippTypes::TO_ADRESS
        ];

        $this->actingAs($user)->post(route('orders.store'), $data)
            ->assertSessionHasNoErrors()
            ->assertRedirectContains('mercadopago');

        $this->assertDatabaseHas('orders', ['address_id' => $data['address_id'], 'product_id' => $data['product_id']]);
        $this->assertDatabaseHas('shipps', ['order_id' => 1]);
    }

    public function test_view_address_trashed()
    {
        $order = Order::factory()->create();

        $order->address->delete();

        $this->assertInstanceOf(Address::class, $order->address);
    }

    public function test_view_product_trashed()
    {
        $order = Order::factory()->create();

        $order->product->delete();

        $this->assertInstanceOf(Product::class, $order->product);
    }

    public function test_store_with_trashed_address()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $address = Address::factory()->create();

        $product = Product::factory()->create();

        $data = [
            'address_id' => $address->id,
            'product_id' => $product->id,
            'quantity' => $product->stock,
            'shipp_type' => ShippTypes::TO_ADRESS
        ];

        $address->delete();

        $this->actingAs($user)->post(route('orders.store'), $data)
            ->assertStatus(404);

        $this->assertDatabaseMissing('orders', ['address_id' => $data['address_id'], 'product_id' => $data['product_id']]);
        $this->assertDatabaseMissing('shipps', ['order_id' => 1]);
    }

    public function test_store_required_validation()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $data = [
            // 'address_id' => 1,
            // 'product_id' => 1',
            // 'quantity' => 0
        ];

        $this->actingAs($user)->post(route('orders.store'), $data)
            ->assertStatus(302)
            ->assertSessionHasErrors(['address_id', 'product_id', 'quantity']);

        $this->assertDatabaseMissing('orders', $data);
    }

    public function test_store_less_than_field_validation()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $address = Address::factory()->create();

        $product = Product::factory(['stock' => 2])->create();

        $data = [
            'address_id' => $address->id,
            'product_id' => $product->id,
            'quantity' => 3
        ];

        $this->actingAs($user)->post(route('orders.store'), $data)
            ->assertStatus(302)
            ->assertSessionHasErrors(['quantity']);

        $this->assertDatabaseMissing('orders', $data);
    }

    public function test_show()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $order = Order::factory(['buyer_id' => $user->id, 'status' => OrderStatus::PAYED])->create();

        $this->actingAs($user)->get(route('orders.show', $order))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Orders/Show')
                    ->has('order')
                    ->has('order.product')
            );
    }

    public function test_show_shipp_status()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $order = Order::factory(['buyer_id' => $user->id, 'status' => OrderStatus::PAYED])->create();

        $shippingGateway = app()->make(ShippGatewayInterface::class);

        $shipment = $shippingGateway->createShipment($order, ShippTypes::TO_ADRESS);

        $tracking_id = $shipment['bultos'][0]['numeroDeEnvio'];

        Shipp::factory()->create(['order_id' => $order->id, 'tracking_id' => $tracking_id]);

        $this->actingAs($user)->get(route('orders.show', $order))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Orders/Show')
                    ->has('order')
                    ->has('order.product')
                    ->has('order.shipp_status')
            );
    }

    public function test_get_label()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);

        $order = Order::factory()->create(['product_id' => $product->id]);

        $shippingGateway = app()->make(ShippGatewayInterface::class);

        $shipment = $shippingGateway->createShipment($order, ShippTypes::TO_ADRESS);

        $tracking_id = $shipment['bultos'][0]['numeroDeEnvio'];

        Shipp::factory()->create(['order_id' => $order->id, 'tracking_id' => $tracking_id]);

        $res = $this->actingAs($user)->get(route('orders.label', $order))
            ->assertSuccessful();

        $this->assertEquals("application/pdf", $res->headers->get('content-type'));
    }

    public function test_get_label_validation()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);

        $order = Order::factory()->create(['product_id' => $product->id]);

        $res = $this->actingAs($user)->get(route('orders.label', $order))
            ->assertStatus(404);
    }

    public function test_show_policy()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $order = Order::factory()->create();

        $this->actingAs($user)->get(route('orders.show', $order))
            ->assertStatus(403);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_paid_webhook()
    {
        $order = Order::factory()->create();

        Shipp::factory()->create(['order_id' => $order->id, 'type' => ShippTypes::TO_ADRESS]);

        /**
         * @var object $mockedPayment
         */
        $mockedPayment = Mockery::mock('alias:MercadoPago\Payment');
        $mockedPayment->status = 'approved';
        $mockedPayment->order_id = 1;
        $mockedPayment->shouldReceive('find_by_id')->andReturn($mockedPayment);

        /**
         * @var object $mockedMerchantOrder
         */
        $mockedMerchantOrder = Mockery::mock('alias:MercadoPago\MerchantOrder');
        $mockedMerchantOrder->preferenceId = 2;
        $mockedMerchantOrder->shouldReceive('find_by_id')->andReturn($mockedMerchantOrder);

        /**
         * @var object $mockedPreference
         */
        $mockedPreference = Mockery::mock('alias:MercadoPago\Preference');
        $mockedPreference->additional_info = json_encode(['order_id' => $order->id]);
        $mockedPreference->shouldReceive('find_by_id')->andReturn($mockedPreference);

        $data = [
            'id' => $this->faker->numberBetween(1, 9999999),
            'live_mode' => true,
            'type' => 'payment',
            'date_created' => Carbon::now(),
            'application_id' => $this->faker->numberBetween(1, 9999999),
            'user_id' => $this->faker->numberBetween(1, 9999999),
            'version' => 1,
            'api_version' => 'v1',
            'action' => 'payment.created',
            'data' => [
                'id' => $this->faker->numberBetween(1, 9999999)
            ]
        ];

        $this->post(route('orders.paid-webhook'), $data)
            ->assertSuccessful();

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => OrderStatus::PAYED]);

        $this->assertDatabaseHas('payments', ['order_id' => $order->id]);

        $this->assertDatabaseHas('chats', ['chateable_id' => $order->id, 'chateable_type' => Order::class]);
    }

    public function test_paid_webhook_validation()
    {
        $data = [
            'data' => [
                // 'id' => 123131
            ]
        ];

        $this->post(route('orders.paid-webhook'), $data, ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    public function test_buys()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $orders = Order::factory(5)->create(['buyer_id' => $user->id]);

        $this->actingAs($user)->get(route('user.buys'))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Orders/Buys')
                    ->has('pagination.data', 5)
            );
    }

    public function test_sells()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user->id]);

        $orders = Order::factory(5)->create(['product_id' => $product->id]);

        $this->actingAs($user)->get(route('user.sells'))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Orders/Sells')
                    ->has('pagination.data', 5)
            );
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_payment_service_unavailable()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $address = Address::factory()->create();

        $product = Product::factory()->create();

        $data = [
            'address_id' => $address->id,
            'product_id' => $product->id,
            'quantity' => $product->stock,
            'shipp_type' => ShippTypes::TO_ADRESS
        ];

        /**
         * @var object $mockedPreference
         */
        $mockedPreference = Mockery::mock('overload:MercadoPago\Preference');
        $mockedPreference->shouldReceive('save')->andReturn(false);

        $this->actingAs($user)->post(route('orders.store'), $data)
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Payments/Error')
                    ->has('status')
            );
    }

    public function test_show_if_not_payed()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = User::factory()->create();

        $order = Order::factory(['buyer_id' => $user->id])->create();

        $shipp = Shipp::factory(['order_id' => $order->id])->create();

        $this->actingAs($user)->get(route('orders.show', $order))
            ->assertSessionHasNoErrors()
            ->assertRedirectContains('mercadopago');
    }
}
