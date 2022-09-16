<?php

namespace App\OrmModel\Metrics;

use App\OrmModel\Gastos\Gasto;
use App\OrmModel\src\Metrics\Trend;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EvolSaldoInversiones extends Trend
{
    protected bool $filtraValoresEnCero = true;

    public function calculate(Request $request): Collection
    {
        return $this->sumByDays($request, Gasto::class, 'monto', 'fecha');
    }

    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->whereIn('cuenta_id', [3, 6, 7])
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
