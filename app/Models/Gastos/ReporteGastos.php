<?php

namespace App\Models\Gastos;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReporteGastos extends Reporte
{
    protected string $campoColumna = 'mes';

    protected string $campoFila = 'tipo_gasto_id';

    protected string $campoDato = 'sum_monto';

    public function __construct(int $cuentaId, int $anno, int $tipoMovimientoId)
    {
        $this->data = Gasto::getDataReporte($cuentaId, $anno, $tipoMovimientoId);

        parent::__construct();
    }

    /** @return Collection<array-key, string> */
    protected function makeTitulosColumnas(): Collection
    {
        return collect(range(1, 12))
            ->combine(range(1, 12))
            ->map(fn ($mes) => trans('fechas.'.Carbon::create(2000, $mes, 1)->format('F')));
    }

    /** @return Collection<array-key, string> */
    protected function makeTitulosFilas(): Collection
    {
        $cantTiposMovimiento = $this->data
            ->map->tipoGasto
            ->map->tipoMovimiento
            ->unique()
            ->count();

        return $this->data
            ->map->tipoGasto
            ->unique()
            ->when($cantTiposMovimiento > 1, function($tiposGasto) {
                return $tiposGasto->map(function($tipoGasto) {
                    return $tipoGasto->setAttribute('tipo_gasto',
                        strtoupper($tipoGasto->tipoMovimiento->tipo_movimiento) . ' - ' . $tipoGasto->tipo_gasto
                    );
                });
            })
            ->sortBy('tipo_gasto')
            ->pluck('tipo_gasto', 'id');
    }
}
