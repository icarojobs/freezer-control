<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatusEnum;
use Filament\Widgets\ChartWidget;
use App\Services\Stats\Charts\StatsChartsOrdersService;
use Carbon\CarbonImmutable;

class ProductOrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Últimas Vendas';
    protected static ?int $sort = 2;
    protected int $totalMaxValue = 0;

    protected function getData(): array
    {
        $orders = StatsChartsOrdersService::getOrders();

        $qtyPerMonthPaid = $qtyPerMonthPending = $qtyPerMonthFailed = array_fill(0, 12, 0);

        foreach ($orders as $order) {

            $month = $order->month;
            $year = $order->year;
            $listMonthsAndYears = generateLast12Months();


            $keyFound = array_keys(array_filter($listMonthsAndYears, function ($item) use ($month, $year) {
                return $item['year'] === $year && $item['month'] === $month;
            }))[0];

            if ($order->status === OrderStatusEnum::PAID) {
                $qtyPerMonthPaid[$keyFound] = $order->total;
            } elseif ($order->status === OrderStatusEnum::PENDING) {
                $qtyPerMonthPending[$keyFound] = $order->total;
            } elseif ($order->status === OrderStatusEnum::FAILED) {
                $qtyPerMonthFailed[$keyFound] = $order->total;
            }
        }

        $this->totalMaxValue = max(array_merge($qtyPerMonthPaid, $qtyPerMonthPending, $qtyPerMonthFailed));

        return [
            'datasets' => [
                [
                    'label' => 'Pago',
                    'data' => $qtyPerMonthPaid,
                    'backgroundColor' => '#47ba6d',
                    'borderColor' => '#47ba6d',
                    'pointBackgroundColor' => '#47ba6d'
                ],
                [
                    'label' => 'Pendente',
                    'data' => $qtyPerMonthPending,
                    'backgroundColor' => '#fbcd0c',
                    'borderColor' => '#fbcd0c',
                    'pointBackgroundColor' => '#fbcd0c'
                ],
                [
                    'label' => 'Com Falha',
                    'data' => $qtyPerMonthFailed,
                    'backgroundColor' => '#ef4636',
                    'borderColor' => '#ef4636',
                    'pointBackgroundColor' => '#ef4636'
                ],
            ],
            'labels' => generateLast12Months('labels'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'min' => 0,
                    'ticks' => [
                        'stepSize' => $this->totalMaxValue > 5 ? 1 : 0
                    ]
                ]
            ]
        ];
    }
}
