<?php

namespace App\Gastos;

use Illuminate\Database\Eloquent\Model;

class TipoMovimiento extends Model
{
    protected $table = 'cta_tipos_movimientos';

    protected $fillable = ['tipo_movimiento', 'signo', 'orden'];


    public static function formArray()
    {
        return static::orderBy('orden')->get()
            ->pluck('tipo_movimiento', 'id');
    }
}
