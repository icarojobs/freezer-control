<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Product;

class NormalizeCostPriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalize:orders-items-cost-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate the cost_price of items in orders if it does not exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::get();

        foreach ($orders as $order) {
            $items = $order->items;

            foreach ($items as $key => $item) {
                if (!isset($item['cost_price'])) {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $items[$key]['cost_price'] = $product->cost_price;
                    }
                }
            }

            $order->update(['items' => $items]);
        }

        $this->info('Cost prices populated successfully.');
    }
}
