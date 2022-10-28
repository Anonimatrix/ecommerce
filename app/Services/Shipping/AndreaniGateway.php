<?php

namespace App\Services\Shipping;

use App\Events\ShipmentCreated;
use App\Exceptions\Shipping\UnavailableServiceException;
use App\Models\Adress;
use App\Models\Order;
use App\Models\Product;
use App\Services\Shipping\Contracts\ShippGatewayInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class AndreaniGateway implements ShippGatewayInterface
{
    protected $baseConfigAndreaniApiPath;
    protected $token;

    protected function urlResolver(string $routeConfig, string $extra = "")
    {
        $baseUrl = Config::get($this->baseConfigAndreaniApiPath . '.url');
        $route = Config::get($this->baseConfigAndreaniApiPath . '.routes.' . $routeConfig);

        return $baseUrl . $route . $extra;
    }

    public function __construct()
    {
        $this->baseConfigAndreaniApiPath = 'shipping.drivers.andreani.api';
        $this->token = $this->getToken('usuario_test', 'DI$iKqMClEtM');
    }

    public function quote(int $buyerPostalCode, Product $product, string $shippType)
    {
        $quoteUrl =  $this->urlResolver('quote');

        $params = [
            'cpDestino' => $buyerPostalCode,
            'contrato' => Config::get($this->baseConfigAndreaniApiPath . '.contracts.' . $shippType),
            'cliente' => Config::get($this->baseConfigAndreaniApiPath . '.client_code'),
            'sucursalOrigen' => $product->sucursalId,
            'bultos[0][valorDeclarado]' => $product->price
        ];

        $res = Http::get($quoteUrl, $params)->json();

        $requestIsFailed = isset($res['status']) && $res['status'] > 300;

        $shippPrice = !$requestIsFailed ? (float) $res['tarifaConIva']['total'] : null;

        return ['price' => $shippPrice, 'status_code' => $requestIsFailed ? $res['status'] : 200];
    }

    public function listSucursales(Adress $sellerAdress)
    {
        $listSucursalesUrl = $this->urlResolver('list_sucursales');

        $params = [
            'codigoPostal' => $sellerAdress->postal_code
        ];

        return Http::get($listSucursalesUrl, $params)->json();
    }

    public function createShipment(Order $order, string $shipmentType)
    {
        $createShipmentUrl = $this->urlResolver('create_shipment');

        $product = $order->product;
        $seller = $product->user;
        $buyer = $order->buyer;
        $buyer_adress = $order->adress;

        $params = [
            'contrato' => (string) Config::get($this->baseConfigAndreaniApiPath . '.contracts.' . $shipmentType),
            'origen' => [
                'sucursal' => ['id' => $product->sucursal_id]
            ],
            'destino' => [
                'postal' => [
                    'localidad' => $buyer_adress->city,
                    'codigoPostal' => $buyer_adress->postal_code,
                    'calle' => $buyer_adress->adress,
                    'numero' => '2255',
                ]
            ],
            'remitente' => [
                'eMail' => $seller->email,
                'nombreCompleto' => $seller->name,
                'documentoTipo' => $seller->dni_type,
                'documentoNumero' => $seller->dni_number,
            ],
            'destinatario' => [[
                'eMail' => $buyer->email,
                'nombreCompleto' => $buyer->name,
                'documentoTipo' => $buyer->dni_type,
                'documentoNumero' => $buyer->dni_number,
            ]],
            'bultos' => [
                [
                    'kilos' => 12,
                    'volumenCm' => 6
                ]
            ]
        ];

        $token = $this->token;

        $res = Http::withHeaders(['x-authorization-token' => $token])->post($createShipmentUrl, $params)->json();;

        ShipmentCreated::dispatch($res, $order);

        return $res;
    }

    public function getLabel(string $tracking_id)
    {
        $labelUrl = $this->urlResolver('create_shipment', "/$tracking_id/etiquetas?bulto=1");

        $token =  $this->token;

        $res = Http::withHeaders(['x-authorization-token' => $token])->get($labelUrl);
        // dd(response()->make($res));
        return $res;
    }

    public function getStatusShipp(string $tracking_id)
    {
        $shippUrl = $this->urlResolver('create_shipment', "/$tracking_id");

        $token =  $this->token;

        $res = Http::withHeaders(['x-authorization-token' => $token])->get($shippUrl);

        return $res['estado'];
    }

    public function getToken(string $user, string $password): string
    {
        $loginUrl = $this->urlResolver('login');

        $encodedUser = base64_encode($user . ':' . $password);

        $headers = ['Authorization' => "Basic $encodedUser"];

        $res = Http::withHeaders($headers)->get($loginUrl);

        if (!array_key_exists('token', json_decode($res->body(), true))) throw new UnavailableServiceException();

        return $res['token'];
    }
}
