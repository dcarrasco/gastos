<?php

namespace App\OrmModel\Metrics;

use App\Models\Gastos\Gasto;
use Illuminate\Http\Request;
use App\Models\Gastos\SaldoMes;
use App\Models\Gastos\Inversion;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Database\Eloquent\Builder;

class UtilInversiones extends Value
{
    protected $cuentasInversiones = [3, 6];

    public function calculate(Request $request): array
    {
        return [
            'currentValue' => $this->calculateUtil($request, $this->currentRange($request)),
            'previousValue' => $this->calculateUtil($request, $this->previousRange($request)),
        ];
    }

    protected function calculateUtil(Request $request, array $range): int
    {
        [$fechaDesde, $fechaHasta] = $range;

        return collect($this->cuentasInversiones)
            ->map(function ($cuenta) use ($fechaHasta) {
                $inversion = new Inversion($cuenta, $fechaHasta->year);

                $ultimoSaldo = $inversion->saldos()
                    ->filter(function ($saldo) use ($fechaHasta) {
                        return $saldo->fecha <= $fechaHasta;
                    })
                    ->last() ?? new Gasto;

                return $inversion->util($ultimoSaldo);
            })
            ->sum();
    }

    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->whereIn('cuenta_id', $this->cuentasInversiones);
    }


    public function ranges(): array
    {
        return [
            'YTD' => 'Year To Date',
        ];
    }
}
