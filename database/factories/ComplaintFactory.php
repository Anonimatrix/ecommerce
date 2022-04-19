<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplaintFactory extends Factory
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
            'intermediary_id' => User::factory(['role_id' => Role::factory(['name' => 'admin'])->create()]),
            'reason' => 'not paid me',
            'status' => 'initialized'
        ];
    }
}
