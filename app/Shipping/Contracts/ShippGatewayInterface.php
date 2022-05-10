<?php

namespace App\Shipping\Contracts;

use App\Models\Adress;
use App\Models\Product;

interface ShippGatewayInterface
{
    public function quote(int $buyerPostalCode, Product $product, string $shippType);

    public function listSucursales(Adress $sellerAdress);
}
