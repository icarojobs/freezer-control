<?php

$version = env('ASAAS_API_VERSION');

return [
    'sandbox' => [
        'url' => env('ASSAS_SANDBOX_URL') . '/' . $version,
        'token' => env('ASAAS_SANDBOX_TOKEN'),
        'webhook' => env('ASAAS_WEBHOOK_TOKEN_SANDBOX'),

    ],
    'production' => [
        'url' => env('ASSAS_PRODUCTION_URL') . '/' . $version,
        'token' => env('ASAAS_PRODUCTION_TOKEN'),
        'webhook' => env('ASAAS_WEBHOOK_TOKEN_PRODUCTION'),
    ],
];
