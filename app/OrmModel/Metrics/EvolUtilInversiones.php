<?php

namespace App\OrmModel\Metrics;

use Illuminate\Http\Request;
use App\OrmModel\Gastos\Gasto;
use Illuminate\Support\Collection;
use App\OrmModel\src\Metrics\Trend;
use Illuminate\Database\Eloquent\Builder;

class EvolUtilInversiones extends Trend
{
    protected $cuentasInversiones = [3, 6];

    public function calculate(Request $request): Collection
    {
        $movimientos = $this->resumenMovimientos($request, Gasto::class, 'monto', 'fecha');

        return $this->sumByDays($request, Gasto::class, 'monto', 'fecha')
            ->filter(function ($valor) {
                return $valor != 0;
            })
            ->map(function ($monto, $fecha) use ($movimientos) {
                $fechaMov = $movimientos->keys()
                    ->filter(function ($fechaMovimiento) use ($fecha) {
                        return $fechaMovimiento <= $fecha;
                    })
                    ->last();

                return $monto - $movimientos->get($fechaMov);
            });
    }

    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->whereIn('cuenta_id', $this->cuentasInversiones)->where('tipo_movimiento_id', 4);
    }

    protected function filterMovimientos(Request $request, Builder $query): Builder
    {
        return $query->whereIn('cuenta_id', $this->cuentasInversiones)
            ->noSaldos()
            ->whereBetween('fecha', $this->currentRange($request));
    }

    protected function resumenMovimientos(Request $request, string $resource, string $sumColumn, string $timeColumn): Collection
    {
        $movimientos = $this->filterMovimientos($request, (new $resource)->getModelQueryBuilder())
            ->get();

        return $movimientos->pluck($timeColumn)
            ->map->format('Y-m-d')
            ->sort()
            ->unique()
            ->mapWithKeys(function ($fecha) use ($movimientos, $sumColumn, $timeColumn) {
                return [$fecha => $movimientos->filter(function ($movimiento) use ($fecha, $timeColumn) {
                    return $movimiento->{$timeColumn}->format('Y-m-d') == $fecha;
                })->sum($sumColumn)];
            })
            ->map(function ($monto) use (&$montoAcum) {
                return $montoAcum += $monto;
            });
    }


    public function ranges(): array
    {
        return [
            'YTD' => 'Year To Date',
        ];
    }
}
