<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use App\Models\Subcategorie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class SubcategorieControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_show()
    {
        $subcategorie = Subcategorie::factory()->create();

        Product::factory(10)->create(['subcategorie_id' => $subcategorie->id]);

        $this->get(route('subcategories.show', 1))
            ->assertSuccessful()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Subcategorie/Show')
                    ->has('products.data', 10)
            );
    }
}
