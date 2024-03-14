<?php

namespace App\Services\PaymentGateway\Connectors\Asaas;

use App\Services\PaymentGateway\Contracts\AdapterInterface;
use App\Services\PaymentGateway\Contracts\WebhookInterface;

class WebHook implements WebhookInterface
{

    public function __construct(
        public AdapterInterface $http,
    ) {
    }

    public function list(): array
    {
        return $this->http->get('/webhooks');
    }

    public function get(string $id): array
    {
        return $this->http->get("/webhooks/{$id}");
    }

    public function create(array $data): array
    {
        return $this->http->post('/webhooks', $data);
    }

    public function update(int|string $id, array $data): array
    {
        return $this->http->put("/webhooks/{$id}", $data);
    }

    public function delete(int|string $id): array
    {
        return $this->http->delete("/webhooks/{$id}");
    }
}
