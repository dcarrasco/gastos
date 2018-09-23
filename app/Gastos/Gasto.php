<?php

namespace App\Gastos;

use App\Acl\Usuario;
use App\Gastos\Cuenta;
use App\Gastos\TipoGasto;
use App\Gastos\TipoMovimiento;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $fillable = [
        'cuenta_id', 'anno', 'mes', 'glosa', 'serie', 'tipo_gasto_id', 'monto', 'tipo_movimiento_id', 'usuario_id',
    ];

    protected $dates = [
        'fecha'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'cta_gastos';
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function tipoGasto()
    {
        return $this->belongsTo(TipoGasto::class);
    }

    public function tipoMovimiento()
    {
        return $this->belongsTo(TipoMovimiento::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
