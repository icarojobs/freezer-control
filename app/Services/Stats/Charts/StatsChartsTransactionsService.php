<?php

namespace App\Services\Stats\Charts;

use App\Models\ProductTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatsChartsTransactionsService
{
    public static function getTransactions(): ?Collection
    {
        $currentDate = now();

        $startDate = $currentDate->copy()->subMonths(11)->startOfMonth();
        $endDate = $currentDate->copy()->endOfMonth();

        return ProductTransaction::select(
            DB::raw('type'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('type', 'year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    }
}
