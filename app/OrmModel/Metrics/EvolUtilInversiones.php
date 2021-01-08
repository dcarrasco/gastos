<?php

namespace App\OrmModel\Metrics;

use Illuminate\Http\Request;
use App\OrmModel\Gastos\Gasto;
use Illuminate\Support\Collection;
use App\OrmModel\src\Metrics\Trend;
use Illuminate\Database\Eloquent\Builder;

class EvolUtilInversiones extends Trend
{
    protected $filtraValoresEnCero = true;

    protected $cuentasInversiones = [3, 6];

    public function calculate(Request $request): Collection
    {
        $movimientos = $this->resumenMovimientos($request, Gasto::class, 'monto', 'fecha');

        return $this->sumByDays($request, Gasto::class, 'monto', 'fecha')
            ->map(function ($saldo, $fechaSaldo) use ($movimientos) {
                return $saldo - $movimientos->filter->isBeforeDate(now()->create($fechaSaldo))->last()->monto;
            });
    }

    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->whereIn('cuenta_id', $this->cuentasInversiones)
            ->where('tipo_movimiento_id', 4);
    }

    protected function movimientosInversiones(Request $request, string $resource): Collection
    {
        return (new $resource())->getModelQueryBuilder()
            ->noSaldos()
            ->whereIn('cuenta_id', $this->cuentasInversiones)
            ->whereBetween('fecha', $this->currentRange($request))
            ->get();
    }

    protected function resumenMovimientos(
        Request $request,
        string $resource,
        string $sumColumn,
        string $timeColumn
    ): Collection {
        $movimientos = $this->movimientosInversiones($request, $resource);

        return $movimientos->pluck($timeColumn)
            ->map->format('Y-m-d')
            ->sort()
            ->unique()
            ->mapWithKeys(function ($fecha) use ($movimientos, $sumColumn, $timeColumn) {
                return [$fecha => (new Gasto())->model()
                    ->setAttribute('fecha', $fecha)
                    ->setAttribute('monto', $movimientos
                        ->filter->isBeforeDate(now()->create($fecha), $timeColumn)
                        ->sum($sumColumn))
                ];
            });
    }

    public function ranges(): array
    {
        return [
            'YTD' => 'Year To Date',
        ];
    }
}
