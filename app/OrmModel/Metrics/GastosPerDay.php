<?php

namespace App\OrmModel\Metrics;

use App\Gastos\Gasto;
use Illuminate\Http\Request;
use App\OrmModel\src\Metrics\Trend;

class GastosPerDay extends Trend
{
    public function calculate(Request $request)
    {
        return $this->sumByDays($request, Gasto::class, 'monto', 'fecha');
    }

    public function ranges()
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

    public function uriKey()
    {
        return 'gastos-per-day';
    }
}
