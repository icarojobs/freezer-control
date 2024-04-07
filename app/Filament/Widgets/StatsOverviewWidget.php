<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\ProductTransaction;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\Product;
use App\Services\Stats\StatsControlPanelService;
use App\Enums\ProductTransactionTypeEnum;

class StatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $productTransactions = StatsControlPanelService::getProductTransactions();
        $totalEarningsByNavSales = StatsControlPanelService::getTotalEarningsByNavSales();
        $totalEarnings = StatsControlPanelService::calculateTotalEarnings($totalEarningsByNavSales);
        $totalSales = StatsControlPanelService::getTotalSales();
        $totalPurchases = StatsControlPanelService::getTotalPurchases(ProductTransactionTypeEnum::BUY->value);

        $totalEarningsLastDays = $this->getTotalLastDays(StatsControlPanelService::getTotalEarningsLastDays(), 30, true);
        $totalSalesLastSevenDays = $this->getTotalLastDays(StatsControlPanelService::getTotalSalesLastSevenDays());
        $totalPurchasesLastSevenDays = $this->getTotalLastDays(StatsControlPanelService::getTotalPurchasesLastDays(), 30);



        $totalSalesFormatted = formatCurrency($productTransactions->totalSales);
        $totalPurchasesFormatted = formatCurrency($productTransactions->totalPurchases);
        $totalEarningsFormatted = formatCurrency($productTransactions->totalEarnings + $totalEarnings);

        return [
            Stat::make('Total em Vendas (R$)', $totalSalesFormatted)
                ->description($totalSales . ' vendas')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning')
                ->chart($totalSalesLastSevenDays),
            Stat::make('Total em Compras (R$)', $totalPurchasesFormatted)
                ->description($totalPurchases . ' compras')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart($totalPurchasesLastSevenDays) //[7, 2, 10, 3, 15, 4, 17]
                ->color('danger'),
            Stat::make('Ganhos Totais (R$)', $totalEarningsFormatted)
                ->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($totalEarningsLastDays)
                ->color('success'),
        ];
    }

    public static function getTotalLastDays(array $groupTotal, $subDays = 7, $earnings = false): array
    {
        $startDate = now()->subDays($subDays)->startOfDay();
        $endDate = now()->endOfDay();

        $collection = collect($groupTotal);

        if ($earnings) {
            $resultOrders = [];
            foreach ($groupTotal['orders_transactions'] as $orders) {
                $created_at = Carbon::parse($orders['created_at'])->toDateString();
                foreach ($orders['items'] as $key => $order) {
                    $resultOrders[$key]['cost_price'] = (int) $order['quantity'] * $order['cost_price'];
                    $resultOrders[$key]['sale_price'] = (float) $order['sub_total'];
                    $resultOrders[$key]['date'] = $created_at;
                }
            }

            $orders = collect($resultOrders)->groupBy('date')->map(function ($items, $date) {
                return [
                    'date' => $items->first()['date'],
                    'total_sales' => $items->sum('sale_price') - $items->sum('cost_price')
                ];
            })->values()->toArray();

            $product = collect($collection['product_transactions'])->groupBy('date')->map(function ($items, $date) {
                return [
                    'date' => $items->first()['date'],
                    'total_sales' => ($items->first()['quantity'] * $items->sum('sale_price')) - ($items->first()['quantity'] * $items->sum('cost_price'))
                ];
            })->values()->toArray();

            $groupTotal = array_merge($orders, $product);
            usort($groupTotal, function ($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });
        } else {
            $groupTotal = $collection->groupBy('date')->map(function ($items, $date) {
                return [
                    'date' => $items->first()['date'],
                    'total_sales' => $items->sum('total_sales')
                ];
            })->values()->toArray();
        }

        $results = [];

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $formattedDate = $date->toDateString();
            $searchIndex = array_search($formattedDate, array_column($groupTotal, "date"));

            if ($searchIndex !== false) {
                $results[$formattedDate] = $groupTotal[$searchIndex]['total_sales'];
            } else {
                $results[$formattedDate] = 0;
            }
        }

        if (count($results) > 7) {
            // array_shift($results);
        }

        // dd(collect($results)->values()->toArray());
        // dd(array_sum(collect($results)->values()->toArray()));
        return collect($results)->values()->toArray();
    }
}
