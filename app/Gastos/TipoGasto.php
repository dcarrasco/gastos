<?php

namespace App\Gastos;

use Illuminate\Database\Eloquent\Model;

class TipoGasto extends Model
{
    protected $fillable = ['tipo_movimiento_id', 'tipo_gasto'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'cta_tipos_gastos';
    }

    public function tipoMovimiento()
    {
        return $this->belongsTo(TipoMovimiento::class);
    }

    public function formArray()
    {
        $tiposGasto = $this->orderBy('tipo_movimiento_id', 'asc')
            ->orderBy('tipo_gasto', 'asc')
            ->get();

        return $tiposGasto->map(function($tipoGasto) {
                return $tipoGasto->tipoMovimiento;
            })
            ->unique()
            ->pluck('id', 'tipo_movimiento')
            ->map(function($tipo_movimiento_id, $tipoMov) use ($tiposGasto) {
                return $tiposGasto->filter(function($tipoGasto) use ($tipo_movimiento_id) {
                        return $tipoGasto->tipo_movimiento_id === $tipo_movimiento_id;
                    })
                    ->pluck('tipo_gasto', 'id')
                    ->all();
            });
    }

    public function nombresTipoGastos(array $idTiposGastos)
    {
        return TipoGasto::orderBy('tipo_gasto')
            ->whereIn('id', array_get($idTiposGastos, 'tipo_gasto_id', []))
            ->get()
            ->pluck('tipo_gasto', 'id');
    }
}
