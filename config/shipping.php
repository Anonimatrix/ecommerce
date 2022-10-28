<?php

use App\Services\Shipping\ShippTypes;

return [
    'drivers' => [
        'andreani' => [
            'api' => [
                'url' => 'https://apisqa.andreani.com',
                'routes' => [
                    'list_sucursales' => '/v2/sucursales',
                    'quote' => '/v1/tarifas',
                    'create_shipment' => '/v2/ordenes-de-envio',
                    'login' => '/login'
                ],
                'contracts' => [
                    ShippTypes::TO_ADRESS => 400006709,
                    ShippTypes::TO_SUCURSAL => 400006711
                ],
                'client_code' => env('ADREANI_CLIENT_CODE', 'CL0003750')
            ]
        ],
    ],
    /**
     * true is when need to pay
     */
    'types' => [
        ShippTypes::TO_ADRESS => 'need to pay',
        ShippTypes::TO_SUCURSAL => 'need to pay',
        ShippTypes::ACCORD_WITH_SELLER => false
    ],
];
