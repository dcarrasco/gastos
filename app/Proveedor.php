<?php

namespace App;

class Proveedor extends OrmModel
{
    public $modelLabel = 'Clasificaci&oacute;n de Almac&eacute;n SAP';

    protected $fillable = [
        'cod_proveedor', 'des_proveedor'
    ];

    protected $guarded = [];

    protected $primaryKey = 'cod_proveedor';
    public $incrementing = false;

    public $modelFields = [
        'cod_proveedor' => [
            'label'          => 'C&oacute;digo del proveedor',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 10,
            'texto_ayuda'    => 'M&aacute;ximo 10 caracteres.',
            'es_id' => true,
            'es_obligatorio' => true,
            'es_unico'       => true,
        ],
        'des_proveedor' => [
            'label'          => 'Nombre del proveedor',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico'       => false,
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
