<?php

namespace App\OrmModel\Metrics;

use App\Gastos\Gasto;
use Illuminate\Http\Request;
use App\OrmModel\src\Metrics\Value;

class NuevosRegistros extends Value
{
    public function calculate(Request $request)
    {
        return $this->count($request, Gasto::class);
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
        return 'nuevos-registros';
    }
}
