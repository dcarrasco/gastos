<?php

namespace App\Models\Gastos;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReporteGastosTotales extends Reporte
{
    protected $campoColumna = 'mes';
    protected $campoFila = 'tipo_gasto_id';
    protected $campoDato = 'monto';

    public function __construct(int $anno)
    {
        $this->data = Gasto::getDataReporteGastosTotales($anno);

        parent::__construct();
    }

    protected function makeTitulosColumnas(): Collection
    {
        return collect(range(1, 12))
            ->combine(range(1, 12))
            ->map(fn($mes) => trans('fechas.' . Carbon::create(2000, $mes, 1)->format('F')));
    }

    protected function makeTitulosFilas(): Collection
    {
        return $this->data
            ->map->tipoGasto
            ->unique()
            ->pluck('tipo_gasto', 'id')
            ->sort();
    }
}
