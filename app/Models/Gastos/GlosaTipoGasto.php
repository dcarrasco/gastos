<?php

namespace App\Models\Gastos;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

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

    public static function getCuenta(int $cuentaId): Collection
    {
        return static::with('tipoGasto', 'tipoGasto.tipoMovimiento')
            ->whereCuentaId($cuentaId)->get();
    }

    public function hasGlosa(string $glosa): bool
    {
        return Str::contains(strtoupper($glosa), strtoupper($this->glosa));
    }
}
