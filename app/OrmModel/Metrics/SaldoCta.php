<?php

namespace App\OrmModel\Metrics;

use App\Models\Gastos\SaldoMes;
use App\OrmModel\Gastos\Gasto;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SaldoCta extends Value
{
    const ID_CUENTA = -1;


    public function calculate(Request $request): array
    {
        return [
            'currentValue' => $this->calculateSaldo($request, $this->currentRange($request)),
            'previousValue' => $this->calculateSaldo($request, $this->previousRange($request)),
        ];
    }

    /**
     * Calcula saldo de la cuenta corriente para un perido de tiempo
     *
     * @param  Request  $request
     * @param  mixed[]  $range
     * @return int
     */
    protected function calculateSaldo(Request $request, array $range): int
    {
        $saldoInicial = SaldoMes::getSaldoMesAnterior(static::ID_CUENTA, $range[1]->year, $range[1]->month);

        $gastos = $this->rangedQuery($request, Gasto::class, 'fecha', $range)
            ->with('tipoMovimiento')
            ->get();

        return $saldoInicial + $gastos->sum('valor_monto');
    }

    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->where('cuenta_id', static::ID_CUENTA);
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
