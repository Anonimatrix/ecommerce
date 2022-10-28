<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentRepository extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'paymentRepository';
    }
}
