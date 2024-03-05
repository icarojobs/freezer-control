<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway\Connectors\Asaas;

use App\Services\PaymentGateway\Connectors\Asaas\Concerns\HasFilter;
use App\Services\PaymentGateway\Contracts\AdapterInterface;
use App\Services\PaymentGateway\Contracts\CustomerInterface;

class Customer implements CustomerInterface
{
    use HasFilter;

    public function __construct(
        public AdapterInterface $http,
    ) {
    }

    public function list(array $filters = []): array
    {
        return $this->http->get('/customers' . $this->filter($filters));
    }

    public function create(array $data): array
    {
        return $this->http->post('/customers', $data);
    }

    public function update(int|string $id, array $data): array
    {
        return $this->http->put('/customers/' . $id, $data);
    }

    public function delete(int|string $id): array
    {
        return $this->http->delete('/customers/' . $id);
    }
}
