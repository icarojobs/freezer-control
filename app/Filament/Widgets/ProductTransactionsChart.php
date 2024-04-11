<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Services\Stats\Charts\StatsChartsService;
use App\Enums\ProductTransactionTypeEnum;
use App\Models\ProductTransaction;

class ProductTransactionsChart extends ChartWidget
{
    protected static ?string $heading = 'Últimas Movimentações';
    protected static ?int $sort = 1;
    protected int $totalMaxValue = 0;

    protected function getData(): array
    {
        $transactionsStatsService = new StatsChartsService(new ProductTransaction());
        $transactions = $transactionsStatsService->getData('type');

        $qtyPerMonthBuy = $qtyPerMonthSale = $qtyPerMonthInventory = array_fill(0, 12, 0);

        foreach ($transactions as $transaction) {
            $month = $transaction->month;
            $year = $transaction->year;
            $listMonthsAndYears = generateLast12Months();

            $keyFound = array_keys(array_filter($listMonthsAndYears, function ($item) use ($month, $year) {
                return $item['year'] === $year && $item['month'] === $month;
            }))[0];

            if ($transaction->type === ProductTransactionTypeEnum::SALE) {
                $qtyPerMonthSale[$keyFound] = $transaction->total;
            } elseif ($transaction->type === ProductTransactionTypeEnum::BUY) {
                $qtyPerMonthBuy[$keyFound] = $transaction->total;
            } elseif ($transaction->type === ProductTransactionTypeEnum::INVENTORY) {
                $qtyPerMonthInventory[$keyFound] = $transaction->total;
            }
        }

        $this->totalMaxValue = max(array_merge($qtyPerMonthBuy, $qtyPerMonthSale, $qtyPerMonthInventory));

        return [
            'datasets' => [
                [
                    'label' => 'Compras',
                    'data' => $qtyPerMonthBuy,
                    'backgroundColor' => '#00b5e1',
                    'borderColor' => '#00b5e1',
                    'pointBackgroundColor' => '#00b5e1'
                ],
                [
                    'label' => 'Vendas',
                    'data' => $qtyPerMonthSale,
                    'backgroundColor' => '#47ba6d',
                    'borderColor' => '#47ba6d',
                    'pointBackgroundColor' => '#47ba6d'
                ],
                [
                    'label' => 'Inventário',
                    'data' => $qtyPerMonthInventory,
                    'backgroundColor' => '#f26c1f',
                    'borderColor' => '#f26c1f',
                    'pointBackgroundColor' => '#f26c1f'
                ],
            ],
            'labels' => generateLast12Months('labels'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
