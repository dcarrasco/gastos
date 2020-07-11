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
        return $this->formattedData([
            'currentValue' => $this->calculateUtil($request, $this->currentRange($request)),
            'previousValue' => $this->calculateUtil($request, $this->previousRange($request)),
        ]);
    }

    protected function calculateUtil(Request $request, array $range): int
    {
        return collect($this->cuentasInversiones)
            ->map(function ($cuenta) use ($range) {
                $inversion = new Inversion($cuenta, $range[1]->year);

                $ultimoSaldo = $inversion->saldos()
                    ->filter(function ($saldo) use ($range) {
                        return $saldo->fecha <= $range[1];
                    })
                    ->last();

                return $inversion->util($ultimoSaldo);
            })
            ->sum();
    }

    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->whereIn('cuenta_id', [3, 6]);
    }


    public function ranges(): array
    {
        return [
            'YTD' => 'Year To Date',
        ];
    }
}
