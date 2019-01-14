<?php

namespace App\Gastos;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class TipoMovimiento extends Model
{
    protected $fillable = ['tipo_movimiento', 'signo'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'cta_tipos_movimientos';
    }

    public static function formArray()
    {
        return static::orderBy('tipo_movimiento')
            ->get()
            ->mapWithKeys(function($tipoMovimiento) {
                return [$tipoMovimiento->getKey() => $tipoMovimiento->tipo_movimiento];
            })->all();
    }
}
