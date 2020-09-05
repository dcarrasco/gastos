<?php

namespace App\Models\Gastos;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReporteGastos extends Reporte
{
    protected $campoColumna = 'mes';
    protected $campoFila = 'tipo_gasto_id';
    protected $campoDato = 'sum_monto';

    public function __construct(int $cuentaId, int $anno, int $tipoMovimientoId)
    {
        $this->data = Gasto::getDataReporte($cuentaId, $anno, $tipoMovimientoId);

        parent::__construct();
    }

    protected function makeTitulosColumnas(): Collection
    {
        return collect(range(1, 12))
            ->combine(range(1, 12))
            ->map(function ($mes) {
                return trans('fechas.' . Carbon::create(2000, $mes, 1)->format('F'));
            });
    }

    protected function makeTitulosFilas(): Collection
    {
        return $this->data
            ->map->tipoGasto
            ->unique()
            ->sortBy('tipo_gasto')
            ->pluck('tipo_gasto', 'id');
    }
}
