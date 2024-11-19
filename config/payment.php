<?php

return [
    'mercadopago' => [
        'public_key' => env('VITE_MERCADO_PAGO_PUBLIC_KEY'),
        'access_token' => env('MERCADO_PAGO_ACCESS_TOKEN'),
        'buyer_nickname' => env('MERCADO_PAGO_BUYER_NICKNAME'),
        'user_id'=> env('MERCADO_PAGO_USER_ID'),
        'app_id'=> env('MERCADO_PAGO_APP_ID'),
    ],
];