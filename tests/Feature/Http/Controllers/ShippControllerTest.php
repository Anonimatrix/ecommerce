<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Adress;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
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
            'apisqa.andreani.com/*' => Http::response(['tarifaConIva' => [
                "seguroDistribucion" => "50",
                "distribucion" => "700",
                "total" => "750"
            ]], 200)
        ]);


        $params = ['product_id' => $product->id, 'shipp_type' => 'shipp_to_adress', 'postal_code' => 7600];

        $this->get(route('shipp.quote', $params))
            ->assertJson(['shipp_price' => 750]);
    }

    public function test_list_sucursales()
    {
        $adress = Adress::factory()->create();

        $this->get(route('shipp.list-sucursales', ['adress_id' => $adress->id]))
            ->assertJsonStructure([
                'sucursales' => [
                    '*' => [
                        'codigo'
                    ]
                ]
            ]);
    }
}
