<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway\Connectors;

use App\Services\PaymentGateway\Connectors\Asaas\Concerns\AsaasConfig;
use App\Services\PaymentGateway\Contracts\AdapterInterface;
use Illuminate\Support\Facades\Http;

class AsaasConnector implements AdapterInterface
{
    use AsaasConfig;

    public function get(string $url)
    {
        try {
            return $this->http
                ->get($url)
                ->throw()
                ->json();
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    public function post(string $url, array $params)
    {
        try {
            return $this->http
                ->post($url, $params)
                ->throw()
                ->json();
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    public function delete(string $url)
    {
        try {
            return $this->http
                ->delete($url)
                ->throw()
                ->json();
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    public function put(string $url, array $params)
    {
        try {
            return $this->http
                ->put($url, $params)
                ->throw()
                ->json();
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }
}
