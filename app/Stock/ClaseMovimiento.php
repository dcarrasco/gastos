<?php

namespace App\Stock;

use App\OrmModel;

class ClaseMovimiento extends OrmModel
{
    public $modelLabel = 'Clase de Movimiento SAP';
    public static $orderField = 'cmv';

    protected $fillable = ['cmv', 'des_cmv'];

    protected $guarded = [];

    protected $primaryKey = 'cmv';
    public $incrementing = false;

    public $modelFields = [
        'cmv' => [
            'label'          => 'C&oacute;digo movimiento',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 10,
            'texto_ayuda'    => 'C&oacute;digo del movimiento. M&aacute;ximo 10 caracteres',
            'es_id'          => true,
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
        'des_cmv' => [
            'label'          => 'Descripci&oacute;n del movimiento',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Descripci&oacute;n del movimiento. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            //'es_unico'       => true
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_cmv_sap');
    }

    public function __toString()
    {
        return (string) $this->cmv.' - '.$this->des_cmv;
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
