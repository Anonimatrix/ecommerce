<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
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
        $tags = Tag::all();

        Product::all()->each(function ($products) use ($tags) {
            $products->tags()->sync(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
