<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ShippGateway extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'shippGateway';
    }
}
