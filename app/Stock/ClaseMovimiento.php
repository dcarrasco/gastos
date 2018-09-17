<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class ClaseMovimiento extends OrmModel
{
    // Eloquent
    public $label = 'Clase de Movimiento SAP';
    protected $fillable = ['cmv', 'des_cmv'];
    protected $primaryKey = 'cmv';
    public $incrementing = false;
    public $timestamps = false;

    // OrmModel
    public $title = 'nom_usuario';
    public $search = [
        'cmv', 'des_cmv'
    ];
    public $modelOrder = 'cmv';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_cmv_sap');
    }

    public function fields() {
        return [
            Text::make('cmv')
                ->sortable()
                ->rules('max:10', 'required', 'unique'),

            Text::make('descripcion','des_cmv')
                ->sortable()
                ->rules('max:50', 'required'),
        ];
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
