<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class MovimientoSap extends OrmModel
{
    public $modelLabel = 'Movimiento SAP';

    public $timestamps = false;
    protected $fillable = [];

    protected $guarded = [];

    protected $primaryKey = '';

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
