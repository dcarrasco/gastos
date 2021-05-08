<?php

namespace App\OrmModel\Metrics;

use Illuminate\Http\Request;
use App\OrmModel\Gastos\Gasto;
use App\Models\Gastos\Inversion;
use Illuminate\Support\Collection;
use App\OrmModel\src\Metrics\Trend;
use Illuminate\Database\Eloquent\Builder;

class EvolUtilInversiones extends Trend
{
    protected $filtraValoresEnCero = true;

    protected $cuentasInversiones = [3, 6, 7];
    protected $movimientoSaldo = 4;

    public function calculate(Request $request): Collection
    {
        $inversiones = $this->inversiones($this->cuentasInversiones, now()->year);

        return $this->sumByDays($request, Gasto::class, 'monto', 'fecha')
            ->map(function ($saldo, $fechaSaldo) use ($inversiones) {
                return $inversiones->map->utilHasta(now()->create($fechaSaldo))->sum();
            });
    }

    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->whereIn('cuenta_id', $this->cuentasInversiones)
            ->where('tipo_movimiento_id', $this->movimientoSaldo);
    }

    protected function inversiones(array $cuentasInversiones, int $anno): Collection
    {
        return collect($cuentasInversiones)
            ->map(function ($cuenta) use ($anno) {
                return new Inversion($cuenta, $anno);
            });
    }

    public function ranges(): array
    {
        return [
            'YTD' => 'Year To Date',
        ];
    }
}
