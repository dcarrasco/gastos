<?php

namespace App\OrmModel\Metrics;

use App\OrmModel\Gastos\Gasto;
use App\OrmModel\src\Metrics\Trend;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RegistrosPorDia extends Trend
{
    public function calculate(Request $request): Collection
    {
        return $this->countByDays($request, Gasto::class, 'fecha');
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
