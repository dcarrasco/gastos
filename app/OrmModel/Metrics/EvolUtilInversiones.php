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
    protected bool $filtraValoresEnCero = true;

    /** @var int[] */
    protected array $cuentasInversiones = [3, 6, 7];

    protected int $movimientoSaldo = 4;

    public function calculate(Request $request): Collection
    {
        $inversiones = collect($this->cuentasInversiones)
            ->map(fn($cuenta) => new Inversion($cuenta, now()->year));

        return $this->sumByDays($request, Gasto::class, 'monto', 'fecha')
            ->map(fn($saldo, $fechaSaldo) => $inversiones->map->utilHasta(now()->create($fechaSaldo))->sum());
    }

    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->whereIn('cuenta_id', $this->cuentasInversiones)
            ->where('tipo_movimiento_id', $this->movimientoSaldo);
    }

    public function ranges(): array
    {
        return [
            'YTD' => 'Year To Date',
        ];
    }
}
