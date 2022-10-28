<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Product;
use App\Models\User;
use App\Statuses\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product = Product::factory()->create();

        return [
            'buyer_id' => User::factory()->create(),
            'product_id' => $product->id,
            'address_id' => Address::factory()->create(),
            'status' => OrderStatus::PENDING,
            'quantity' => $this->faker->numberBetween(1, 20),
            'unit_price' => $product->price
        ];
    }
}
