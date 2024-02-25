<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway\Connectors\Asaas\Concerns;

trait HasFilter
{

    public function filter(array $filters = []):string
    {
       return empty($filters) ? '' : '?' . http_build_query($filters);
    }
}
