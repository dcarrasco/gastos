<?php

namespace App\Gastos;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReporteGastosTotales extends Reporte
{
    protected $campoColumna = 'mes';
    protected $campoFila = 'tipo';
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
            ->map(function ($mes) {
                return trans('fechas.' . Carbon::create(2000, $mes, 1)->format('F'));
            });
    }

    protected function makeTitulosFilas(): Collection
    {
        return $this->data
            ->map->tipo
            ->unique()
            ->sort()
            ->mapWithKeys(function ($tipo) {
                return [$tipo => $tipo];
            });
    }
}
