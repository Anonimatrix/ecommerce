<?php

namespace Database\Factories;

use App\Models\Subcategorie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(4),
            'user_id' => User::factory()->create(),
            'subcategorie_id' => Subcategorie::factory()->create(),
            'shipp_active' => '1',
            'sucursal_id' => 10035,
            'description' => $this->faker->text(800),
            'stock' => $this->faker->numberBetween(1, 20),
            'price' => $this->faker->numberBetween(0, 20000),
            'paused_at' => null
        ];
    }
}
