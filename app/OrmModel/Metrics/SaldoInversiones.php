<?php

namespace App\OrmModel\Metrics;

use App\Models\Gastos\Inversion;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Http\Request;

class SaldoInversiones extends Value
{
    protected array $cuentasInversiones = [3, 6, 7];

    public function calculate(Request $request): array
    {
        return [
            'currentValue' => $this->calculateSaldo($request, $this->currentRange($request)),
            'previousValue' => $this->calculateSaldo($request, $this->previousRange($request)),
        ];
    }

    /**
     * Calcula el saldo de una inversión para un periodo de tiempos
     *
     * @param  Request  $request
     * @param  mixed[]  $range
     * @return int
     */
    protected function calculateSaldo(Request $request, array $range): int
    {
        [$fechaDesde, $fechaHasta] = $range;

        return collect($this->cuentasInversiones)
            ->map(fn ($cuenta) => (new Inversion($cuenta, $fechaHasta->year))->saldos()->last()?->monto)
            ->sum();
    }

    public function ranges(): array
    {
        return [
            'YTD' => 'Year To Date',
        ];
    }
}
