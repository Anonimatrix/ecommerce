<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $order = Order::factory()->create();
        return [
            'order_id' => $order->id,
            'type' => 'ml',
            'amount' => $order->product->price
        ];
    }
}
