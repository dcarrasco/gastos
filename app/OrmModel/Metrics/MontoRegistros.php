<?php

namespace App\OrmModel\Metrics;

use App\OrmModel\Gastos\Gasto;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MontoRegistros extends Value
{
    public function calculate(Request $request): array
    {
        return $this->sum($request, Gasto::class, 'monto');
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
