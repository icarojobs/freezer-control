<?php

namespace App\Services\AsaasPhp\Concerns;

use App\Models\Customer;

trait AsaasClient
{
    public function __construct(
        protected ?string $environment = null,
        protected ?string $token = null,
        protected ?string $url = null,
        protected array $data = [],
    ) {
        $this->environment = app()->isLocal() ? 'sandbox' : 'production';
        $this->token = config("asaas.{$this->environment}.token");
        $this->url = config("asaas.{$this->environment}.url");
    }
}


