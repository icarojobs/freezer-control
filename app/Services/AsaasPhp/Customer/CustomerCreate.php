<?php

declare(strict_types=1);

namespace App\Services\AsaasPhp\Customer;

use App\Services\AsaasPhp\Concerns\AsaasClient;
use App\Services\AsaasPhp\Contracts\AsaasPaymentInterface;
use Illuminate\Support\Facades\Http;

class CustomerCreate implements AsaasPaymentInterface
{
    use AsaasClient;

    public function handle(): array
    {
        try {
            return Http::withHeader('access_token', $this->token)
                ->post("{$this->url}/customers", $this->data)
                ->throw()
                ->json();
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }
}
