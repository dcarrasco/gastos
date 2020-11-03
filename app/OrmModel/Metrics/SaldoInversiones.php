<?php

namespace App\OrmModel\Metrics;

use Illuminate\Http\Request;
use App\OrmModel\Gastos\Gasto;
use Illuminate\Support\Collection;
use App\OrmModel\src\Metrics\Trend;
use Illuminate\Database\Eloquent\Builder;

class SaldoInversiones extends Trend
{
    public function calculate(Request $request): Collection
    {
        return $this->sumByDays($request, Gasto::class, 'monto', 'fecha')
            ->filter(function ($valor) {
                return $valor != 0;
            });
    }

    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->whereIn('cuenta_id', [3, 6])
            ->where('tipo_movimiento_id', 4);
    }

    public function ranges(): array
    {
        return [
            'YTD' => 'Year To Date',
            'QTD' => 'Quarter To Date',
            'MTD' => 'Month To Date',
        ];
    }
}
