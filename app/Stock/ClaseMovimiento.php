<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;

class ClaseMovimiento extends Model
{
    public $label = 'Clase de Movimiento SAP';
    protected $fillable = ['cmv', 'des_cmv'];
    protected $primaryKey = 'cmv';
    public $incrementing = false;
    public $timestamps = false;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_cmv_sap');
    }

    public static function transaccionesConsumoToa()
    {
        return ['Z35', 'Z45', 'Z39', 'Z41', 'Z87', 'Z89'];
    }

    public static function transaccionesAsignacionToa()
    {
        return ['Z31', 'Z32'];
    }
}
