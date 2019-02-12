<?php

namespace App\Gastos;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class TipoMovimiento extends Model
{
    protected $fillable = ['tipo_movimiento', 'signo', 'orden'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'cta_tipos_movimientos';
    }

    public static function formArray()
    {
        return static::orderBy('orden')->get()
            ->pluck('tipo_movimiento', 'id');
    }
}
