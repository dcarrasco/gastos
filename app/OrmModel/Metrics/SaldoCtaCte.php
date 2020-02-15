<?php

namespace App\OrmModel\Metrics;

use App\Gastos\SaldoMes;
use Illuminate\Http\Request;
use App\OrmModel\src\Metrics\Value;

class SaldoCtaCte extends Value
{
    public function calculate(Request $request): array
    {
        return $this->formattedData([
            'currentValue' => SaldoMes::where('cuenta_id', 1)->latest()->first()->saldo_final,
            'previousValue' => 0,
        ]);
    }

    public function ranges(): array
    {
        return [
            'CURR_MONTH' => 'Current Month',
        ];
    }

    public function uriKey(): string
    {
        return 'saldo-cta-cte';
    }
}
