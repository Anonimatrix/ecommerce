<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ShippRepository extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'shippRepository';
    }
}
