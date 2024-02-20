<?php

declare(strict_types=1);

namespace App\Services\AsaasPhp\Customer;

use App\Services\AsaasPhp\Concerns\AsaasClient;
use Illuminate\Support\Facades\Http;

class CustomerList
{
    use AsaasClient;

    public function list(): array
    {
        try {
            return Http::withHeader('access_token', $this->token)
                ->get("{$this->url}/customers")
                ->throw()
                ->json();
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }
}
