<?php

namespace App\OrmModel\Metrics;

use App\Models\Gastos\SaldoMes;
use App\OrmModel\Gastos\Gasto;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SaldoCta extends Value
{
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
        $gastos = $this->rangedQuery($request, Gasto::class, 'fecha', $range)
            ->with('tipoMovimiento')
            ->get();

        if ($gastos->count() > 0) {
            $saldoInicial = SaldoMes::where('cuenta_id', static::ID_CUENTA)
                ->where('anno', $range[0]->year)
                ->where('mes', $range[0]->month)
                ->first();

            $saldoInicial = (int) (optional($saldoInicial)->saldo_inicial ?: 0);
        } else {
            $saldoInicial = SaldoMes::where('cuenta_id', static::ID_CUENTA)
                ->where('anno', $range[1]->copy()->subMonth()->year)
                ->where('mes', $range[1]->copy()->subMonth()->month)
                ->first();

            $saldoInicial = (int) (optional($saldoInicial)->saldo_final ?: 0);

        }

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
