<?php

declare(strict_types=1);


use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Gateway;
use Illuminate\Support\Facades\Artisan;

Artisan::command('play', function () {
    $adapter = app(AsaasConnector::class);

    $gateway = new Gateway($adapter);

    // Insere um novo cliente
    $customer = $gateway->customer()->create([
        'name' => 'Fabiano Fernandes',
        'cpfCnpj' => '21115873709',
        'email' => 'fabianofernandes@test.com.br',
        'mobilePhone' => '16992222222',
    ]);

    dd($customer);
    // Atualizar um cliente
    $customer = $gateway->customer()->update('cus_000005892683', [
        'name' => 'Tio Jobs',
        'cpfCnpj' => '21115873709',
        'email' => 'tiojobs@test.com.br',
        'mobilePhone' => '16992222222',
    ]);

    dd($customer);

    // Retorna a listagem de clientes
    $customers = $gateway->customer()->list();

    // Retorna clientes utilizando filtros
    $customers = $gateway->customer()->list(['cpfCnpj' => '21115873709']);

    // Remove um cliente
    $customer = $gateway->customer()->delete('cus_000005892683');

    // Criar uma nova cobrança
    $data = [
        "billingType" => "PIX", // "CREDIT_CARD", "PIX", "BOLETO
        "discount" => [
            "value" => 10,
            "dueDateLimitDays" => 0
        ],
        "interest" => [
            "value" => 2
        ],
        "fine" => [
            "value" => 1
        ],
        "customer" => "cus_000005892683",
        "dueDate" => "2024-02-29",
        "value" => 100,
        "description" => "Pedido 056984",
        "daysAfterDueDateToCancellationRegistration" => 1,
        "externalReference" => "056984",
        "postalService" => false
    ];

    $payment = $gateway->payment()->create($data);

    // Retorna a listagem de cobranças
    $payments = $gateway->payment()->list(['customer' => 'cus_000005892683']);

    dd($payments);

});
