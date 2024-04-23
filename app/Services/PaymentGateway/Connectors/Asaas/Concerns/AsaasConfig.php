<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway\Connectors\Asaas\Concerns;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

trait AsaasConfig
{
    public function __construct(
        protected ?PendingRequest $http = null,
    ) {
        $environment = app()->isLocal() ? 'sandbox' : 'production';
        $token = config("asaas.{$environment}.token");
        $url = config("asaas.{$environment}.url");

        $this->http = Http::withHeader('access_token', $token)->baseUrl($url);
    }

}
