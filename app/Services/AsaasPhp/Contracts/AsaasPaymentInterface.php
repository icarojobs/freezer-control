<?php

declare(strict_types=1);

namespace App\Services\AsaasPhp\Contracts;

interface AsaasPaymentInterface
{
    public function handle(): array;
}
