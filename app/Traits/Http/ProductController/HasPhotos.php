<?php

namespace App\Traits\Http\ProductController;

use App\Models\Photo;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

trait HasPhotos
{
    public function uploadPhotos($product, $photos)
    {
        foreach ($photos as $photo) {
            $filename = time() . $photo->getClientOriginalName();
            $path = "products/$filename";
            Storage::disk('photos')->put($path, $photo);
            Photo::create([
                'photoable_type' => Product::class,
                'photoable_id' => $product->id,
                'path' => $path
            ]);
        }
    }
}
