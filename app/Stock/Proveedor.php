<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class Proveedor extends OrmModel
{
    public $modelLabel = 'Proveedor';

    public $timestamps = false;
    protected $fillable = [
        'cod_proveedor', 'des_proveedor'
    ];

    protected $guarded = [];

    protected $primaryKey = 'cod_proveedor';

    public $incrementing = false;

    public $modelFields = [
        'cod_proveedor' => [
            'label' => 'C&oacute;digo del proveedor',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 10,
            'textoAyuda' => 'M&aacute;ximo 10 caracteres.',
            'esId' => true,
            'esObligatorio' => true,
            'esUnico' => true,
        ],
        'des_proveedor' => [
            'label' => 'Nombre del proveedor',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            'esUnico' => false,
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_proveedores');
    }

    public function __toString()
    {
        return (string) $this->des_proveedor;
    }
}
