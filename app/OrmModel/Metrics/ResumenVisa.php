<?php

namespace App\OrmModel\Metrics;

use Illuminate\Http\Request;
use App\OrmModel\Gastos\Gasto;
use Illuminate\Support\Collection;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Database\Eloquent\Builder;

class ResumenVisa extends Value
{
    public function calculate(Request $request): array
    {
        return $this->sum($request, Gasto::class, 'monto', 'fecha');
    }

    protected function extendFilter(Request $request, Builder $query): Builder
    {
        return $query = $query->where('cuenta_id', 2)
            ->where('tipo_movimiento_id', 1);
    }

    public function ranges(): array
    {
        return [
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            'YTD' => 'Year To Date',
            'CURR_MONTH' => 'Current Month',
        ];
    }
}
