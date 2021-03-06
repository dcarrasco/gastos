<?php

namespace App\OrmModel\Metrics;

use Illuminate\Http\Request;
use App\OrmModel\Gastos\Gasto;
use Illuminate\Support\Collection;
use App\OrmModel\src\Metrics\Partition;
use Illuminate\Database\Eloquent\Builder;

class GastoVisa extends Partition
{
    public function calculate(Request $request): Collection
    {
        return $this->sum($request, Gasto::class, 'tipo_gasto', 'monto', 'tipoGasto');
    }

    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->where('cuenta_id', 2)
            ->whereBetween('fecha', $this->currentRange($request));
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
