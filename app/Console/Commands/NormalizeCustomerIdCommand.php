<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\AsaasPhp\Customer\CreateCustomerFromModel;
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

        $customers->each(function (Customer $customer) {
            (new CreateCustomerFromModel($customer))->send();
        });

        return 0;
    }
}
