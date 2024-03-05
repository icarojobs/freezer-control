<?php

namespace App\Services\PaymentGateway\Contracts;

interface PaymentInterface
{

    public function list(array $filters = []): array;

    public function create(array $data): array;

    public function update(int|string $id, array $data): array;

    public function delete(int|string $id): array;

    public function getPaymentStatus(int|string $id): array;

    public function getInvoiceCode(int|string $id): array;

    public function getPixQrCode(int|string $id): array;

}
