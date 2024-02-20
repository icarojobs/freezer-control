<?php

declare(strict_types=1);

namespace App\Services\AsaasPhp\Customer;

use Illuminate\Support\Facades\Http;

class CustomerList
{
    public static function list(): array
    {
        $environment = app()->isLocal() ? 'sandbox' : 'production';

        try {
            return Http::withToken(config("{$environment}.token"))
                ->get(config("{$environment}.url") . "/customers")
                ->throw()
                ->json();
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }
}
