<?php

namespace App\OrmModel\Metrics;

use App\Gastos\Gasto;
use App\OrmModel\Value;
use Illuminate\Http\Request;

class MontoRegistros extends Value
{
    public function calculate(Request $request)
    {
        return $this->sum($request, Gasto::class, 'monto');
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
        return 'monto-registros';
    }
}
