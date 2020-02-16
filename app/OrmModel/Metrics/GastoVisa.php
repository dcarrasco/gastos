<?php

namespace App\OrmModel\Metrics;

use Illuminate\Http\Request;
use App\OrmModel\Gastos\Gasto;
use Illuminate\Support\Facades\DB;
use App\OrmModel\src\Metrics\Partition;

class GastoVisa extends Partition
{
    public function calculate(Request $request): array
    {
        $datos = [];

        (new Gasto)->model()
            ->whereBetween('fecha', $this->currentDateInterval($request))
            ->where('cuenta_id', 2)
            ->get()
            ->each(function ($gasto) use (&$datos) {
                if (!array_key_exists($gasto->tipoGasto->tipo_gasto, $datos)) {
                    $datos[$gasto->tipoGasto->tipo_gasto] = 0;
                }
                $datos[$gasto->tipoGasto->tipo_gasto] += $gasto->monto;
            });

        return collect($datos)->map(function ($value, $key) {
            return ['grupo' => $key, 'cant' => $value];
        })
        ->values()
        ->all();
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
        return 'gasto-visa';
    }
}
