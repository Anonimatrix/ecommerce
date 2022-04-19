<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create(),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'adress' => $this->faker->address(),
            'postal_code' => $this->faker->postcode()
        ];
    }
}
