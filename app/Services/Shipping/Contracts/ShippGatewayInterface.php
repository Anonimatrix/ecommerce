<?php

namespace App\Services\Shipping\Contracts;

use App\Models\Adress;
use App\Models\Order;
use App\Models\Product;

interface ShippGatewayInterface
{
    public function quote(int $buyerPostalCode, Product $product, string $shippType);

    public function listSucursales(Adress $sellerAdress);

    public function createShipment(Order $order, string $shipmentType);

    public function getLabel(string $tracking_id);

    public function getToken(string $user, string $password): string;

    public function getStatusShipp(string $tracking_id);
}
