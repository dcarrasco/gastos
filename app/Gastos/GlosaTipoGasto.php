<?php

namespace App\Gastos;

use Illuminate\Database\Eloquent\Model;

class GlosaTipoGasto extends Model
{
    protected $table = 'cta_glosa_tipo_gasto';

    protected $fillable = [
        'cuenta_id', 'glosa', 'tipo_gasto_id',
    ];


    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function tipoGasto()
    {
        return $this->belongsTo(TipoGasto::class);
    }

    public static function getCuenta($cuentaId = 0)
    {
        return static::with('tipoGasto', 'tipoGasto.tipoMovimiento')->whereCuentaId($cuentaId)->get();
    }
}
