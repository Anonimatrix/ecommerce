<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OrderRepository extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'orderRepository';
    }
}
