<?php

namespace App\Models\Gastos;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReporteGastosTotales extends Reporte
{
    protected string $campoColumna = 'mes';
    protected string $campoFila = 'tipo_gasto_id';
    protected string $campoDato = 'monto';


    public function __construct(int $anno)
    {
        $this->data = Gasto::getDataReporteGastosTotales($anno);

        parent::__construct();
    }

    /** @return Collection<array-key, string> */
    protected function makeTitulosColumnas(): Collection
    {
        return collect(range(1, 12))
            ->combine(range(1, 12))
            ->map(fn($mes) => trans('fechas.' . Carbon::create(2000, $mes, 1)->format('F')));
    }

    /** @return Collection<array-key, string> */
    protected function makeTitulosFilas(): Collection
    {
        return $this->data
            ->map->tipoGasto
            ->unique()
            ->pluck('tipo_gasto', 'id')
            ->sort();
    }
}
