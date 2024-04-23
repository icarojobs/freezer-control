<?php

declare(strict_types=1);


use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Gateway;
use Illuminate\Support\Facades\Artisan;

Artisan::command('play', function () {
//    $chargeId = 'pay_qeh5d7oe2s1odisx';
//
//    $adapter = app(AsaasConnector::class);
//
//    $gateway = new Gateway($adapter);

    // Criar uma nova cobrança
//    $data = [
//        "billingType" => "PIX", // "CREDIT_CARD", "PIX", "BOLETO
//        "discount" => [
//            "value" => 10,
//            "dueDateLimitDays" => 0
//        ],
//        "interest" => [
//            "value" => 2
//        ],
//        "fine" => [
//            "value" => 1
//        ],
//        "customer" => "cus_000005908684",
//        "dueDate" => "2024-03-27",
//        "value" => 100,
//        "description" => "Pedido 056984",
//        "daysAfterDueDateToCancellationRegistration" => 1,
//        "externalReference" => "056984",
//        "postalService" => false
//    ];
//
//    $payment = $gateway->payment()->create($data);
//
//    dd($payment);

    // Retorna a listagem de cobranças
    $adapter = app(AsaasConnector::class);

    $gateway = new Gateway($adapter);

    $customers = $gateway->customer()->list();
    dd($customers);

});
