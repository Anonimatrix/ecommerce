<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Address;
use App\Models\Product;
use App\Services\Shipping\ShippTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia;
use Mockery;
use Tests\TestCase;

class ShippControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_quote()
    {
        $product = Product::factory()->create();

        /**
         * @var object $http
         */

        Http::fake([
            "https://apisqa.andreani.com/v1/*" => Http::response(['tarifaConIva' => [
                "seguroDistribucion" => "50",
                "distribucion" => "700",
                "total" => "750"
            ]], 200)
        ]);


        $params = ['product_id' => $product->id, 'shipp_type' => ShippTypes::TO_ADRESS, 'postal_code' => 7600];

        $this->get(route('shipp.quote', $params))
            ->assertJson(['shipp_price' => 750]);
    }

    public function test_list_sucursales()
    {
        $address = Address::factory()->create();

        $this->get(route('shipp.list-sucursales', ['address_id' => $address->id]))
            ->assertJsonStructure([
                'sucursales' => [
                    '*' => [
                        'codigo'
                    ]
                ]
            ]);
    }

    public function test_payment_service_unavailable()
    {
        $product = Product::factory()->create();

        /**
         * @var object $http
         */

        Http::fake([
            "https://apisqa.andreani.com/*" => Http::response(['message' => "unavailable"], 200)
        ]);

        $params = ['product_id' => $product->id, 'shipp_type' => ShippTypes::TO_ADRESS, 'postal_code' => 7600];

        $this->get(route('shipp.quote', $params))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Shipping/Error')
                    ->has('status')
            );
    }
}
