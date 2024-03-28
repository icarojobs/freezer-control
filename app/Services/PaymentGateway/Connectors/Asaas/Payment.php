<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway\Connectors\Asaas;

use App\Services\PaymentGateway\Connectors\Asaas\Concerns\HasFilter;
use App\Services\PaymentGateway\Contracts\AdapterInterface;
use App\Services\PaymentGateway\Contracts\PaymentInterface;

class Payment implements PaymentInterface
{

    use HasFilter;
    public function __construct(
        public AdapterInterface $http,
    ) {
    }

    public function list(array $filters = []): array
    {
        return $this->http->get('/payments' . $this->filter($filters));
    }

    public function get(int|string $id): array
    {
        return $this->http->get("/payments/{$id}");
    }

    public function create(array $data): array
    {
        return $this->http->post('/payments', $data);
    }

    public function payWithCreditCard(string $id, array $data): array
    {
        return $this->http->post("/payments/{$id}/payWithCreditCard", $data);
    }

    public function update(int|string $id, array $data): array
    {
        return $this->http->put("/payments/{$id}", $data);
    }

    public function delete(int|string $id): array
    {
        return $this->http->delete("/payments/{$id}");
    }

    public function getPaymentStatus(int|string $id): array
    {
        return $this->http->get("/payments/{$id}/status" );
    }

    public function getInvoiceCode(int|string $id): array
    {
        return $this->http->get("/payments/{$id}/identificationField" );
    }

    public function getPixQrCode(int|string $id): array
    {
        return $this->http->get("/payments/{$id}/pixQrCode" );
    }
}
