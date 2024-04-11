<?php

namespace App\Services\Stats\Charts;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class StatsChartsService
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getData(string $searchField): ?Collection
    {
        $currentDate = now();

        $startDate = $currentDate->copy()->subMonths(11)->startOfMonth();
        $endDate = $currentDate->copy()->endOfMonth();

        return $this->model->select(
            DB::raw($searchField),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy($searchField, 'year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    }
}
