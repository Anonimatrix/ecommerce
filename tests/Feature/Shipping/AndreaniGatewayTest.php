<?php

namespace Tests\Feature\Shipping;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Shipping\AndreaniGateway;
use App\Services\Shipping\Contracts\ShippGatewayInterface;
use App\Services\Shipping\ShippTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AndreaniGatewayTest extends TestCase
{
    use RefreshDatabase;

    public function test_listSucursales()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();
        $address = Address::factory(['user_id' => $user->id])->create();

        $shippingGateway = app()->make(ShippGatewayInterface::class);

        $sucursales = $shippingGateway->listSucursales($address);

        $this->assertNotEmpty($sucursales);
        $this->assertArrayHasKey('codigo', $sucursales[0]);
    }

    public function test_quote()
    {
        $buyerPostalCode = 7600;
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable $user
         */
        $user = User::factory()->create();
        $address = Address::factory(['user_id' => $user->id])->create();
        $product = Product::factory(['user_id' => $user->id])->create();

        $shippingGateway = app()->make(ShippGatewayInterface::class);

        $quote = $shippingGateway->quote($buyerPostalCode, $product, ShippTypes::TO_ADRESS);

        $this->assertNotEmpty($quote);
        $this->assertIsFloat($quote['price']);
    }

    public function test_create_shipment()
    {
        $order = Order::factory()->create();

        $shippingGateway = app()->make(ShippGatewayInterface::class);

        $shipment = $shippingGateway->createShipment($order, ShippTypes::TO_ADRESS);

        $this->assertEquals($shipment['estado'], "Pendiente");
        $this->assertCount(1, $shipment['bultos']);
    }

    public function test_get_label()
    {
        $order = Order::factory()->create();

        $shippingGateway = app()->make(ShippGatewayInterface::class);

        $shipment = $shippingGateway->createShipment($order, ShippTypes::TO_ADRESS);

        $tracking_id = $shipment['bultos'][0]['numeroDeEnvio'];

        $label = $shippingGateway->getLabel($tracking_id);

        $this->assertEquals("application/pdf", $label->getHeader('Content-Type')[0]);
    }
}
