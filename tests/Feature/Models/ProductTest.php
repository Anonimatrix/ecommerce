<?php

namespace Tests\Feature\Models;

use App\Models\Product;
use App\Models\Subcategorie;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_products_has_many_tags()
    {
        $product = new Product();

        $this->assertInstanceOf(Collection::class, $product->tags);
    }

    public function test_products_has_many_photos()
    {
        $product = $product = new Product();

        $this->assertInstanceOf(Collection::class, $product->photos);
    }

    public function test_belongs_to_subcategorie()
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(Subcategorie::class, $product->subcategorie);
    }

    public function test_belongs_to_user()
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(User::class, $product->user);
    }

    public function test_has_many_orders()
    {
        $product = $product = new Product();

        $this->assertInstanceOf(Collection::class, $product->orders);
    }
}
