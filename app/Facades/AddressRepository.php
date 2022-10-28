<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AddressRepository extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'addressRepository';
    }
}
