<?php

namespace App\Gastos;

use Illuminate\Support\Carbon;

class ReporteGastos extends Reporte
{
    protected $campoColumna = 'mes';
    protected $campoFila = 'tipo_gasto_id';
    protected $campoDato = 'sum_monto';

    public function __construct($cuentaId, $anno, $tipoMovimientoId)
    {
        $this->data = Gasto::getDataReporte($cuentaId, $anno, $tipoMovimientoId);

        parent::__construct();
    }

    protected function makeTitulosColumnas()
    {
        return collect(range(1,12))->combine(range(1,12))->map(function($mes) {
            return trans('fechas.'.Carbon::create(2000, $mes, 1)->format('F'));
        });
    }

    protected function makeTitulosFilas()
    {
        return $this->data
            ->map->tipoGasto
            ->unique()
            ->sortBy('tipo_gasto')
            ->pluck('tipo_gasto', 'id');
    }
}
