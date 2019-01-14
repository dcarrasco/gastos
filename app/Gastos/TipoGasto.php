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

    public static function formArray()
    {
        $tiposGasto = static::orderBy('tipo_movimiento_id')
            ->orderBy('tipo_gasto')
            ->get();

        return $tiposGasto->map(function($tipoGasto) {
                return $tipoGasto->tipoMovimiento;
            })->unique()
            ->pluck('id', 'tipo_movimiento')
            ->map(function($tipoMovimientoId, $tipoMov) use ($tiposGasto) {
                return $tiposGasto->filter(function($tipoGasto) use ($tipoMovimientoId) {
                        return $tipoGasto->tipo_movimiento_id === $tipoMovimientoId;
                    })->pluck('tipo_gasto', 'id')
                    ->all();
            });
    }

    public static function nombresTipoGastos(array $idTiposGastos)
    {
        return TipoGasto::orderBy('tipo_gasto')
            ->whereIn('id', array_get($idTiposGastos, 'tipo_gasto_id', []))
            ->get()
            ->pluck('tipo_gasto', 'id');
    }
}
