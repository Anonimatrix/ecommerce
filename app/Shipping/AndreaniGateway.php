<?php

namespace App\Shipping;

use App\Models\Adress;
use App\Models\Product;
use App\Shipping\Contracts\ShippGatewayInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class AndreaniGateway implements ShippGatewayInterface
{
    protected $baseConfigAndreaniApiPath;

    public function __construct()
    {
        $this->baseConfigAndreaniApiPath = 'shipping.andreani.api';
    }

    public function quote(int $buyerPostalCode, Product $product, string $shippType)
    {
        $quoteUrl = Config::get($this->baseConfigAndreaniApiPath . '.url') .
            Config::get($this->baseConfigAndreaniApiPath . '.uris.quote');

        $params = [
            'cpDestino' => $buyerPostalCode,
            'contrato' => Config::get($this->baseConfigAndreaniApiPath . '.contracts.' . $shippType),
            'cliente' => Config::get($this->baseConfigAndreaniApiPath . '.client_code'),
            'sucursalOrigen' => $product->sucursalCode,
            'bultos[0][valorDeclarado]' => $product->price
        ];

        return Http::get($quoteUrl, $params)->json();
    }

    public function listSucursales(Adress $sellerAdress)
    {
        $listSucursalesUrl =
            Config::get($this->baseConfigAndreaniApiPath . '.url') .
            Config::get($this->baseConfigAndreaniApiPath . '.uris.list_sucursales');

        $params = [
            'codigoPostal' => $sellerAdress->postal_code
        ];

        return Http::get($listSucursalesUrl, $params)->json();
    }
}
