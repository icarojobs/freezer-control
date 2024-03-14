<?php

declare(strict_types=1);


namespace App\Models;

use Sushi\Sushi;
use Illuminate\Database\Eloquent\Model;
use App\Services\PaymentGateway\Gateway;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\PaymentGateway\Connectors\AsaasConnector;

class WebhookWrapper extends Model
{

    use Sushi;

    public $gateway;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $adapter = app(AsaasConnector::class);
        $this->gateway = app(Gateway::class, ['adapter' => $adapter]);
    }

    public function getRows()
    {
        return [
            [
                'id' => 1,
                'url' => 'https://www.exemplo.com/webhook/asaas',
                'email' => 'wH8Qs@example.com',
                'interrupted' => false,
                'enabled' => true,
                'authToken' => '5tLxsL6uoN'
            ],
        ];
    }
}
