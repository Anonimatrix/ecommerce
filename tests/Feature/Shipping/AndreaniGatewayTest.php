<?php

namespace Tests\Feature\Shipping;

use App\Models\Adress;
use App\Models\Product;
use App\Models\User;
use App\Shipping\AndreaniGateway;
use App\Shipping\ShippGatewayInterface;
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
        $adress = Adress::factory(['user_id' => $user->id])->create();

        $shippingGateway = app()->make(ShippGatewayInterface::class);

        $sucursales = $shippingGateway->listSucursales($adress);

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
        $adress = Adress::factory(['user_id' => $user->id])->create();
        $product = Product::factory(['user_id' => $user->id])->create();

        $shippingGateway = app()->make(ShippGatewayInterface::class);

        $quote = $shippingGateway->quote($buyerPostalCode, $product, 'shipp_to_adress');

        $this->assertNotEmpty($quote);
        $this->assertArrayHasKey('tarifaConIva', $quote);
        $this->assertArrayHasKey('total', $quote['tarifaConIva']);
    }
}
