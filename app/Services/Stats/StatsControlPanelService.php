<?php

namespace App\Services\Stats;

use App\Models\Order;
use App\Models\ProductTransaction;
use App\Enums\OrderStatusEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Enums\ProductTransactionTypeEnum;


class StatsControlPanelService
{
    public static function getTotalProductTransactions(): ?array
    {
        $sale = ProductTransactionTypeEnum::SALE->value;
        $buy = ProductTransactionTypeEnum::BUY->value;
        $paid = OrderStatusEnum::PAID->value;

        $query = ProductTransaction::selectRaw('
        SUM(
            CASE 
                WHEN type = "' . $sale . '" THEN 
                    (quantity * (
                        SELECT sale_price 
                        FROM products 
                        WHERE products.id = product_transactions.product_id AND product_transactions.type = "' . $sale . '"
                    ))
                ELSE 
                    0 
            END
        ) AS totalSales,
        (
            SELECT SUM(total) 
            FROM orders 
            WHERE status = "' . $paid . '"
        ) as totalSalesOrders,
        SUM(
            CASE
                WHEN type = "' . $buy . '" THEN
                (quantity * (
                    SELECT cost_price 
                    FROM products 
                    WHERE products.id = product_transactions.product_id
                )) 
            END
        ) AS totalPurchases,
        SUM(
            CASE 
                WHEN type = "' . $sale . '" THEN 
                    (quantity * (
                        SELECT sale_price 
                        FROM products 
                        WHERE products.id = product_transactions.product_id
                    )) 
                    - (quantity * (
                        SELECT cost_price 
                        FROM products 
                        WHERE products.id = product_transactions.product_id
                    )) 
                ELSE 
                    0 
            END
        ) AS totalEarnings
        ')->first();

        $queryEarningsOrder = Order::select('items')->where('status', OrderStatusEnum::PAID->value)->pluck('items');
        $totalEarningsOrders = 0;

        $queryEarningsOrder->each(function ($items) use (&$totalEarningsOrders) {
            foreach ($items as $item) {
                if (isset($item['cost_price'])) {
                    $totalEarningsOrders += ($item['quantity'] * $item['unit_price']) - ($item['quantity'] * $item['cost_price']);
                }
            }
        });

        return [
            'totalSales' => $query->totalSales + $query->totalSalesOrders,
            'totalPurchases' => $query->totalPurchases,
            'totalEarnings' => $query->totalEarnings + $totalEarningsOrders,
        ];
    }

    public static function getTotalEarningsByNavSales(): Collection
    {
        $query = Order::select('items')->where('status', OrderStatusEnum::PAID->value)->pluck('items')->collapse();
        return $query;
    }

    public static function getTotalSales(): string
    {
        $query = ProductTransaction::select(
            DB::raw('COUNT(*) as total_sales'),
            DB::raw('(SELECT COUNT(*) FROM orders WHERE status = "' . OrderStatusEnum::PAID->value . '") as total_orders')
        )
            ->where('type', ProductTransactionTypeEnum::SALE->value)
            ->first()->toArray();

        return array_sum($query);
    }

    public static function getTotalPurchases(): string
    {
        $query = ProductTransaction::select(
            DB::raw('COUNT(*) as total_sales'),
        )
            ->where('type', ProductTransactionTypeEnum::BUY->value)
            ->first()->toArray();

        return array_sum($query);
    }
}
