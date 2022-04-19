<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = Role::all();

        Product::all()->each(function ($products) use ($tags) {
            $products->tags()->sync(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
