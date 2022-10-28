<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store()
    {
        /**
         * @var \App\Models\User $user
         */

        $user = User::factory()->create();

        $product = Product::factory()->create();

        $data = ['product_id' => $product->id];

        $this->actingAs($user)->post(
            route('views.store'),
            $data
        )->assertSuccessful();

        $this->assertDatabaseHas('views', $data);
    }

    public function test_store_validation()
    {
        /**
         * @var \App\Models\User $user
         */

        $user = User::factory()->create();

        $data = ['product_id' => 1];

        $headers = [
            'Accept' => 'application/json'
        ];

        $this->actingAs($user)->post(
            route('views.store'),
            $data,
            $headers
        )->assertStatus(422);
    }
}
