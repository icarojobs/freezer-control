<?php

namespace App\Services\Stats;

use App\Models\Order;
use App\Models\ProductTransaction;
use App\Enums\OrderStatusEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Enums\ProductTransactionTypeEnum;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;


class StatsControlPanelService
{
    protected ?string $startDate = null;
    protected ?string $endDate = null;

    public function __construct(?string $startDate, ?string $endDate)
    {
        $this->startDate = $startDate ?? Carbon::parse('2000-01-01')->format('Y-m-d 00:00:00');
        $this->endDate = $endDate ?? date('Y-m-d 23:59:59');
    }

    public function getTotalProductTransactions(): ?array
    {
        $sale = ProductTransactionTypeEnum::SALE->value;
        $buy = ProductTransactionTypeEnum::BUY->value;
        $paid = OrderStatusEnum::PAID->value;

        $datesBinding = $this->generateBindings(9);

        $query = ProductTransaction::selectRaw('
        SUM(
            CASE 
                WHEN type = "' . $sale . '" THEN 
                    (quantity * (
                        SELECT sale_price 
                        FROM products 
                        WHERE products.id = product_transactions.product_id 
                        AND product_transactions.type = "' . $sale . '"
                    AND (date(product_transactions.created_at) BETWEEN ? AND ?)
                    ))
                ELSE 
                    0 
            END
        ) AS totalSales,
        (
            SELECT SUM(total) 
            FROM orders 
            WHERE status = "' . $paid . '"
            AND (date(created_at) BETWEEN ? AND ?)
        ) as totalSalesOrders,
        SUM(
            CASE
                WHEN type = "' . $buy . '" THEN
                (quantity * (
                    SELECT cost_price 
                    FROM products 
                    WHERE products.id = product_transactions.product_id
                    AND (date(product_transactions.created_at) BETWEEN ? AND ?)
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
                        AND (date(product_transactions.created_at) BETWEEN  ? AND ?)
                    )) 
                    - (quantity * (
                        SELECT cost_price 
                        FROM products 
                        WHERE products.id = product_transactions.product_id
                        AND (date(product_transactions.created_at) BETWEEN  ? AND ?)
                    )) 
                ELSE 
                    0 
            END
        ) AS totalEarnings
        ')
            ->setBindings($datesBinding)
            ->first();

        $queryEarningsOrder = Order::select('items')->where('status', OrderStatusEnum::PAID->value)
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)->pluck('items');

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

    public function getTotalSales(): string
    {
        $datesBinding = $this->generateBindings(1);

        $countProductTransaction = (ProductTransaction::whereRaw('(date(created_at) BETWEEN ? AND ?) AND type = "' . ProductTransactionTypeEnum::SALE->value . '"', $datesBinding)->count());

        $countOrders = (Order::whereRaw('(date(created_at) BETWEEN ? AND ?) AND status = "' . OrderStatusEnum::PAID->value . '"', $datesBinding)->count());

        return $countProductTransaction + $countOrders ?? '0';
    }

    public function getTotalPurchases(): string
    {
        return ProductTransaction::whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->where('type', ProductTransactionTypeEnum::BUY->value)
            ->count();
    }

    private function generateBindings(int $qty = 1): array
    {
        return array_map(function ($index) {
            return $index % 2 == 0 ? $this->startDate : Carbon::parse($this->endDate)->format('Y-m-d 23:59:59');
        }, range(0, $qty));
    }
}
