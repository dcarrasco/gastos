<?php

namespace App\OrmModel\Metrics;

use Illuminate\Http\Request;
use App\OrmModel\Gastos\Gasto;
use Illuminate\Support\Collection;
use App\OrmModel\src\Metrics\Value;

class ResumenGastos extends Value
{
    public function calculate(Request $request): array
    {
        $currentDateInterval = $this->dateInterval($request);
        $previousDateInterval = $this->dateInterval($request, 'previous');

        return $this->formattedData([
            'currentValue' => $this->fetchSumData($request, Gasto::class, 'monto', 'fecha', $currentDateInterval),
            'previousValue' => $this->fetchSumData($request, Gasto::class, 'monto', 'fecha', $previousDateInterval),
        ]);
    }

    protected function fetchSumData(
        Request $request,
        string $resource = '',
        string $sumColumn = '',
        string $timeColumn = '',
        array $dateInterval = []
    ): int {
        return (new Gasto())->model()
            ->whereBetween($timeColumn, $dateInterval)
            ->where('cuenta_id', 1)
            ->where('tipo_movimiento_id', 1)
            ->sum($sumColumn);
    }


    public function ranges(): array
    {
        return [
            'CURR_MONTH' => 'Current Month',
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            'YTD' => 'Year To Date',
        ];
    }

    public function uriKey(): string
    {
        return 'resumen-gastos';
    }
}
