<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'photoable_type' => Product::class,
            'photoable_id' => Product::factory()->create(),
            'url' => $this->faker->imageUrl()
        ];
    }
}
