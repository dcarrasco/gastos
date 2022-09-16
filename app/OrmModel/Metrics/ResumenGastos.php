<?php

namespace App\OrmModel\Metrics;

use App\OrmModel\Gastos\Gasto;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ResumenGastos extends Value
{
    public function calculate(Request $request): array
    {
        return $this->sum($request, Gasto::class, 'monto', 'fecha');
    }

    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->where('cuenta_id', 1)
            ->where('tipo_movimiento_id', 1);
    }

    public function ranges(): array
    {
        return [
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            'YTD' => 'Year To Date',
            'LAST_MONTH' => 'Last Month',
        ];
    }
}
