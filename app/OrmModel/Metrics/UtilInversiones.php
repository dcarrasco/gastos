<?php

namespace App\OrmModel\Metrics;

use App\Gastos\SaldoMes;
use Illuminate\Http\Request;
use App\OrmModel\Gastos\Gasto;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Database\Eloquent\Builder;

class UtilInversiones extends Value
{
    public function calculate(Request $request): array
    {
        return $this->formattedData([
            'currentValue' => $this->calculateUtil($request, $this->currentRange($request)),
            'previousValue' => $this->calculateUtil($request, $this->previousRange($request)),
        ]);
    }

    protected function calculateUtil(Request $request, array $range): int
    {
        $abonos = $this->rangedQuery($request, Gasto::class, 'fecha', $range)
            ->where('tipo_movimiento_id', '<>', 4)
            ->get()
            ->pluck('valor_monto')
            ->sum();

        $ultimaFechaSaldo = $this->rangedQuery($request, Gasto::class, 'fecha', $range)
            ->where('tipo_movimiento_id', 4)
            ->latest('fecha')
            ->first()
            ->fecha;

        $saldo = $this->rangedQuery($request, Gasto::class, 'fecha', $range)
            ->where('tipo_movimiento_id', 4)
            ->where('fecha', $ultimaFechaSaldo)
            ->get()
            ->pluck('valor_monto')
            ->sum();

        return $saldo - $abonos;
    }

    protected function extendFilter(Request $request, Builder $query): Builder
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
