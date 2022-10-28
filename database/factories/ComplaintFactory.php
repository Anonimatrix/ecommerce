<?php

namespace Database\Factories;

use App\Statuses\ComplaintStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        return [
            'order_id' => Order::factory()->create(),
            'intermediary_id' => $user,
            'reason' => 'not paid me',
            'status' => ComplaintStatus::STARTED
        ];
    }
}
