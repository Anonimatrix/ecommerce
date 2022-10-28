<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AdressRepository extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'adressRepository';
    }
}
