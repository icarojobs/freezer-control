<?php

namespace App\Services\Stats\Charts;

use App\Models\Order;
use App\Models\ProductTransaction;
use App\Enums\OrderStatusEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Enums\ProductTransactionTypeEnum;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class StatsChartsOrdersService
{
    public static function getOrders(): ?Collection
    {
        $currentDate = now();

        $startDate = $currentDate->copy()->subMonths(11)->startOfMonth();
        $endDate = $currentDate->copy()->endOfMonth();

        return Order::select(
            DB::raw('status'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status', 'year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    }
}
