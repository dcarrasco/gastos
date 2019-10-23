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
        return static::whereCuentaId($cuentaId)->get();
    }

    public function getPorGlosa($cuenta_id = 0, $glosa = '')
    {
        $glosaTipoGasto = $this->where('cuenta_id', $cuenta_id)
            ->get()
            ->first(function($glosaTipoGasto) use ($glosa) {
                return strpos(strtoupper($glosa), strtoupper($glosaTipoGasto->glosa)) !== false;
            });

        return optional($glosaTipoGasto)->tipo_gasto_id;
    }
}
