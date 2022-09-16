<?php

namespace App\OrmModel\Metrics;

use App\OrmModel\Gastos\Gasto;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Http\Request;

class NuevosRegistros extends Value
{
    public function calculate(Request $request): array
    {
        return $this->count($request, Gasto::class);
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
