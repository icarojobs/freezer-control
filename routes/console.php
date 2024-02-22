<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

Artisan::command('play', function () {
    $data = [
        'name' => 'Rick Tortorelli',
        'cpfCnpj' => '21115873709',
        'email' => 'rick@test.com.br',
        'mobilePhone' => '16992222222',
    ];

    $customer = (new App\Services\AsaasPhp\Customer\CustomerCreate(data: $data))->handle();

    dd($customer);
});
