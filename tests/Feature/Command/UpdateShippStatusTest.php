<?php

namespace Tests\Feature\Command;

use App\Events\ShipmentCreated;
use App\Listeners\ShipmentCreatedListener;
use App\Models\Order;
use App\Models\Shipp;
use App\Services\Shipping\Contracts\ShippGatewayInterface;
use App\Services\Shipping\ShippTypes;
use App\Statuses\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;

class UpdateShippStatusTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_command()
    {
        $order = Order::factory()->create(['status' => OrderStatus::PAYED]);

        Shipp::factory()->create(['order_id' => $order->id]);

        $shippingGateway = app()->make(ShippGatewayInterface::class);

        $shipment = $shippingGateway->createShipment($order, ShippTypes::TO_ADRESS);

        /**
         * @var object $mockedShipmentGateway
         */
        $mockedShipmentGateway = Mockery::mock('alias:App\Facades\ShippGateway');
        $mockedShipmentGateway->shouldReceive('getStatusShipp')->andReturn('Entregado');

        $this->artisan('shipps:update')->assertSuccessful();
        sleep(1);
        $this->assertDatabaseHas('orders', ['status' => OrderStatus::COMPLETED]);
    }
}
