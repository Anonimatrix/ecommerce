<?php

namespace App\Traits\Http;

use App\Models\Photo;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

trait HasPhotos
{
    public function uploadPhotos($item, $photos)
    {
        foreach ($photos as $photo) {
            $filename = time() . $photo->getClientOriginalName();
            $path = "{$item->getTable()}/$filename";
            Storage::disk('photos')->put($path, $photo);
            Photo::create([
                'photoable_type' => get_class($item),
                'photoable_id' => $item->id,
                'path' => $path
            ]);
        }
    }
}
