<?php

namespace App\Services\PaymentGateway\Contracts;

interface WebhookInterface
{

    public function list(): array;

    public function create(array $data): array;

    public function get(string $id): array;

    public function update(int|string $id, array $data): array;

    public function delete(int|string $id): array;

}
