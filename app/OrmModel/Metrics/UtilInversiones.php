<?php

namespace App\OrmModel\Metrics;

use App\Gastos\SaldoMes;
use App\Gastos\Inversion;
use Illuminate\Http\Request;
use App\OrmModel\Gastos\Gasto;
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
        $fechaHasta = $range[1];

        return collect($this->cuentasInversiones)
            ->map(function ($cuenta) use ($fechaHasta) {
                $inversion = new Inversion($cuenta, $fechaHasta->year);

                $ultimoSaldo = $inversion->saldos()
                    ->filter(function ($saldo) use ($fechaHasta) {
                        return $saldo->fecha <= $fechaHasta;
                    })
                    ->last();

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
