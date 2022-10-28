<?php

namespace App\Services\Shipping;

use Illuminate\Support\Facades\Config;

class ShippingUtils
{
    public static function isNeededPay($shipp_type)
    {
        $typesOfShipments = Config::get('shipping.types');

        if ($shipp_type && $typesOfShipments[$shipp_type] === 'need to pay') return true;
        return false;
    }
}
