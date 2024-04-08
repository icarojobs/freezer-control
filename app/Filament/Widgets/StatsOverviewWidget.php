<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Services\Stats\StatsControlPanelService;
use App\Enums\ProductTransactionTypeEnum;

class StatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $productTransactions = StatsControlPanelService::getTotalProductTransactions();
        $totalSales = StatsControlPanelService::getTotalSales();
        $totalPurchases = StatsControlPanelService::getTotalPurchases(ProductTransactionTypeEnum::BUY->value);

        $totalSalesFormatted = formatCurrency($productTransactions['totalSales']);
        $totalPurchasesFormatted = formatCurrency($productTransactions['totalPurchases']);
        $totalEarningsFormatted = formatCurrency($productTransactions['totalEarnings']);
        $percentEarnings = sprintf("%.2f", ($productTransactions['totalEarnings'] / $productTransactions['totalSales']) * 100);

        return [
            Stat::make('Total em Vendas (R$)', $totalSalesFormatted)
                ->description($totalSales . ' vendas')
                ->descriptionIcon('heroicon-m-arrow-up-right')
                ->color('warning'),
            Stat::make('Total em Compras (R$)', $totalPurchasesFormatted)
                ->description($totalPurchases . ' compras')
                ->descriptionIcon('heroicon-m-arrow-down-right')
                ->color('danger'),
            Stat::make('Ganhos Totais (R$)', $totalEarningsFormatted)
                ->description($percentEarnings . '% de lucro')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
