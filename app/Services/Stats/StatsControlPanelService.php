<?php

namespace App\Services\Stats;

use App\Models\Order;
use App\Models\ProductTransaction;
use App\Enums\OrderStatusEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Enums\ProductTransactionTypeEnum;
use Carbon\Carbon;

use function PHPSTORM_META\map;

class StatsControlPanelService
{
    public static function getProductTransactions(): ?ProductTransaction
    {

        $query = ProductTransaction::with('product')->select(['product_id', 'type', 'quantity'])->where('type', 'sale')->get();

        $result = $query->map(function ($item) {
            return $item->quantity * $item->product->sale_price;
        });

        $totalSalesProductTransaction = array_sum($result->toArray());

        $queryOrders = Order::selectRaw('GROUP_CONCAT(items) as items, DATE(created_at) as created_date')
            ->where('status', OrderStatusEnum::PAID->value)
            ->groupBy('created_date')
            ->get();

        dd($queryOrders);
        $resultOrders = $queryOrders->map(function ($item) {
            return $item->quantity * $item->product->sale_price;
        });
        dd($resultOrders);


        $query = ProductTransaction::selectRaw('
        SUM(CASE WHEN type = "sale" THEN (quantity * (SELECT sale_price FROM products WHERE products.id = product_transactions.product_id)) ELSE 0 END) AS totalSales, 
        SUM(CASE WHEN type = "buy" THEN quantity * (SELECT cost_price FROM products WHERE products.id = product_transactions.product_id) ELSE 0 END) AS totalPurchases,
        SUM(CASE WHEN type = "sale" THEN (quantity * (SELECT sale_price FROM products WHERE products.id = product_transactions.product_id)) - (quantity * (SELECT cost_price FROM products WHERE products.id = product_transactions.product_id)) ELSE 0 END) AS totalEarnings
        ')->first();
        dd($query);
        return $query;
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

    public static function getTotalSalesLastSevenDays(): array
    {
        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->endOfDay();

        $totalSalesByDayTransaction = ProductTransaction::selectRaw('DATE(created_at) as date, COUNT(*) as total_sales')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', ProductTransactionTypeEnum::SALE->value)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        $totalSalesByDayOrders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total_sales')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', OrderStatusEnum::PAID->value)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        return array_merge($totalSalesByDayOrders, $totalSalesByDayTransaction);
    }

    public static function getTotalPurchasesLastDays(): array
    {
        $startDate = now()->subDays(7)->startOfDay();
        $endDate = now()->endOfDay();

        $totalSalesByDayTransaction = ProductTransaction::selectRaw('DATE(created_at) as date, COUNT(*) as total_sales')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', ProductTransactionTypeEnum::BUY->value)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        return $totalSalesByDayTransaction;
    }

    public static function getTotalEarningsLastDays(): array
    {
        $startDate = now()->subDays(30)->startOfDay();
        $endDate = now()->endOfDay();

        $totalSalesByDayTransaction = ProductTransaction::selectRaw('DATE(product_transactions.created_at) as date, quantity, products.cost_price, products.sale_price')
            ->join('products', 'product_id', 'products.id')
            ->whereBetween('product_transactions.created_at', [$startDate, $endDate])
            ->where('type', ProductTransactionTypeEnum::SALE->value)
            ->orderBy('date')
            ->get()
            ->toArray();

        $totalSalesByDayTransactionOrders = Order::select('items', 'total', 'created_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', OrderStatusEnum::PAID->value)
            ->orderBy('created_at')
            ->get()
            ->toArray();

        // return $totalSalesByDayTransactionOrders;

        return ['product_transactions' => $totalSalesByDayTransaction, 'orders_transactions' => $totalSalesByDayTransactionOrders];

        // return array_merge($totalSalesByDayTransaction, $totalSalesByDayTransactionOrders);
    }

    public static function calculateTotalEarnings(Collection $totalEarningsByNavSales): float
    {
        return $totalEarningsByNavSales->map(function ($item) {
            $quantity = (int) $item['quantity'];
            $unitPrice = $item['unit_price'];
            $costPrice = $item['cost_price'];

            return ($quantity * $unitPrice) - ($quantity * $costPrice);
        })->sum();
    }
}
