<?php

namespace App\Stock;

use App\OrmModel;

class MovimientoSap extends OrmModel
{
    public $modelLabel = 'Movimiento SAP';

    protected $fillable = ['centro', 'cod_almacen', 'des_almacen', 'uso_almace', 'icono'];

    protected $guarded = [];

    protected $primaryKey = ['centro', 'cod_almacen'];
    public $incrementing = false;

    public $modelFields = [
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_movimientos_sap');
    }

    public function __toString()
    {
        return (string) $this->cmv;
    }
}
