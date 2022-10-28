<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ProductRepository extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'productRepository';
    }
}
