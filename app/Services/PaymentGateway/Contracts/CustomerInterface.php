<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway\Contracts;

interface CustomerInterface
{

    public function list(array $filters = []): array;

    public function create(array $data): array;

    public function update(int|string $id, array $data): array;

    public function delete(int|string $id): array;
}
