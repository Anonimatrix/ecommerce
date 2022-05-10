<?php

return [
    'andreani' => [
        'api' => [
            'url' => 'https://apisqa.andreani.com',
            'uris' => [
                'list_sucursales' => '/v2/sucursales',
                'quote' => '/v1/tarifas'
            ],
            'contracts' => [
                'shipp_to_adress' => 400006709,
                'shipp_to_sucursal' => 400006711
            ],
            'client_code' => env('ADREANI_CLIENT_CODE', 'CL0003750')
        ]
    ]
];
