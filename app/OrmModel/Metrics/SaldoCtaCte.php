<?php

namespace App\OrmModel\Metrics;

use Illuminate\Http\Request;
use App\OrmModel\Gastos\Gasto;
use App\Models\Gastos\SaldoMes;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Database\Eloquent\Builder;

class SaldoCtaCte extends Value
{
    public function calculate(Request $request): array
    {
        return [
            'currentValue' => $this->calculateSaldo($request, $this->currentRange($request)),
            'previousValue' => $this->calculateSaldo($request, $this->previousRange($request)),
        ];
    }

    protected function calculateSaldo(Request $request, array $range): int
    {
        $gastos = $this->rangedQuery($request, Gasto::class, 'fecha', $range)->get();

        $saldoInicial = SaldoMes::where('cuenta_id', 1)
            ->where('anno', $gastos->max('anno'))
            ->where('mes', $gastos->max('mes'))
            ->first();

        return (optional($saldoInicial)->saldo_inicial ?: 0) + $gastos->sum('valor_monto');
    }


    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->where('cuenta_id', 1);
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
