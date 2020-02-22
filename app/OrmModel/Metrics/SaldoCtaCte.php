<?php

namespace App\OrmModel\Metrics;

use App\Gastos\SaldoMes;
use Illuminate\Http\Request;
use App\OrmModel\Gastos\Gasto;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Database\Eloquent\Builder;

class SaldoCtaCte extends Value
{
    public function calculate(Request $request): array
    {
        return $this->formattedData([
            'currentValue' => $this->calculateSaldo($request, $this->currentRange($request)),
            'previousValue' => $this->calculateSaldo($request, $this->previousRange($request)),
        ]);
    }

    protected function calculateSaldo(Request $request, array $range): int
    {
        $gastos = $this->rangedQuery($request, Gasto::class, 'fecha', $range)->get();
        $saldo = SaldoMes::where('cuenta_id', 1)
            ->where('anno', $gastos->max('anno'))
            ->where('mes', $gastos->max('mes'))
            ->first()
            ->saldo_inicial;

        return $saldo + $gastos->sum('valor_monto');
    }


    protected function extendFilter(Request $request, Builder $query): Builder
    {
        return $query->where('cuenta_id', 1);
    }


    public function ranges(): array
    {
        return [
            'MTD' => 'Month To Date',
        ];
    }
}
