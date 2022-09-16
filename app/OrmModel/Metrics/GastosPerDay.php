<?php

namespace App\OrmModel\Metrics;

use App\OrmModel\Gastos\Gasto;
use App\OrmModel\src\Metrics\Trend;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GastosPerDay extends Trend
{
    public function calculate(Request $request): Collection
    {
        return $this->sumByDays($request, Gasto::class, 'monto', 'fecha');
    }

    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->whereIn('cuenta_id', [1, 2]);
    }

    public function ranges(): array
    {
        return [
            30 => '30 Days',
            60 => '60 Days',
            365 => '365 Days',
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            'YTD' => 'Year To Date',
        ];
    }
}
