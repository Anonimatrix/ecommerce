<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'closed' => $this->faker->numberBetween(0, 1),
            'chateable_id' => Order::factory()->create(),
            'chateable_type' => Order::class
        ];
    }
}
