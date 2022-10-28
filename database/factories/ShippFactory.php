<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;

class ShippFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => Order::factory()->create(),
            'tracking_id' => $this->faker->numberBetween(10000, 10000000),
            'type' => array_keys(Config::get('shipping.types'))[$this->faker->numberBetween(0, 2)]
        ];
    }
}
