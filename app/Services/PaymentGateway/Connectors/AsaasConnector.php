<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway\Connectors;

use App\Services\PaymentGateway\Connectors\Asaas\Concerns\AsaasConfig;
use App\Services\PaymentGateway\Contracts\AdapterInterface;

class AsaasConnector implements AdapterInterface
{
    use AsaasConfig;

    public function get(string $url)
    {
        $request = $this->http
            ->get($url);

        try {
            return $request
                ->throw()
                ->json();
        } catch (\Exception) {
            return ['error' => json_decode($request->body(), true)];
        }
    }

    public function post(string $url, array $params)
    {
        $request = $this->http->post($url, $params);

        try {
            return $request
                ->throw()
                ->json();
        } catch (\Exception) {
            return ['error' => json_decode($request->body(), true)];
        }
    }

    public function delete(string $url)
    {
        $request = $this->http->delete($url);

        try {
            return $request
                ->throw()
                ->json();
        } catch (\Exception) {
            return ['error' => json_decode($request->body(), true)];
        }
    }

    public function put(string $url, array $params)
    {
        $request = $this->http->put($url, $params);

        try {
            return $request
                ->throw()
                ->json();
        } catch (\Exception) {
            return ['error' => json_decode($request->body(), true)];
        }
    }
}
