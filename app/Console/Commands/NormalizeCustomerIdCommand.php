<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Gateway;
use Illuminate\Console\Command;

class NormalizeCustomerIdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalize:customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $customers = Customer::query()
            ->whereNull('customer_id')
            ->orWhere('customer_id', '')
            ->get();

        $adapter = new AsaasConnector();
        $gateway = new Gateway($adapter);

        $customers->each(function (Customer $customer) use ($gateway) {
            $data = [
                'name' => $customer->name,
                'cpfCnpj' => sanitize($customer->document),
                'email' => $customer->email,
                'mobilePhone' => sanitize($customer->mobile),
            ];

            $response = $gateway->customer()->create($data);

            if (!isset($response['id']) && is_string($response['error'])) {
                $this->line("Erro ao atualizar {$customer->name}: {$response['error']}");
                return true;
            }

            if (!isset($response['id']) && is_array($response['error'])) {
                $error = $response['error'][0]['description'] ?? 'Erro de integração';
                $this->line("Erro ao atualizar {$customer->name}: {$error}");
                return true;
            }

            $customer->customer_id = $response['id'];
            $customer->save();
            return true;
        });

        return 0;
    }
}
