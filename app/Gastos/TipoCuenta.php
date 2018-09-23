<?php

namespace App\Gastos;

use Illuminate\Database\Eloquent\Model;

class TipoCuenta extends Model
{
    protected $fillable = ['tipo_cuenta'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'cta_tipos_cuentas';
    }
}
