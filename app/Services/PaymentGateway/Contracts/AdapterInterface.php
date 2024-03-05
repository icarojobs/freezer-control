<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway\Contracts;

interface AdapterInterface
{

    public function get(string $url);

    public function post(string $url, array $params);

    public function delete(string $url);

    public function put(string $url, array $params);
}
