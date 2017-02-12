<?php

namespace App\Stock;

use App\OrmModel;

class AlmacenSap extends OrmModel
{
    public $modelLabel = 'Almac&eacute;n SAP';

    protected $fillable = [
        'centro', 'cod_almacen', 'des_almacen', 'uso_almace', 'icono',
    ];

    protected $guarded = [];

    protected $primaryKey = ['centro', 'cod_almacen'];
    public $incrementing = false;

    public $modelFields = [
        'centro' => [
            'label'          => 'Centro',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 10,
            'texto_ayuda'    => 'C&oacute;digo SAP del centro. M&aacute;ximo 10 caracteres.',
            'es_id'          => true,
            'es_obligatorio' => true,
        ],
        'cod_almacen' => [
            'label'          => 'Almac&eacute;n',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 10,
            'texto_ayuda'    => 'C&oacute;digo SAP del almac&eacuten. M&aacute;ximo 10 caracteres.',
            'es_id'          => true,
            'es_obligatorio' => true,
        ],
        'des_almacen' => [
            'label'          => 'Descripci&oacute;n Almac&eacute;n',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Descripci&oacute;n del almac&eacuten. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
        ],
        'uso_almacen' => [
                'label'          => 'Uso Almac&eacute;n',
                'tipo'           => OrmModel::TIPO_CHAR,
                'largo'          => 50,
                'texto_ayuda'    => 'Indica para que se usa el almac&eacute;n. M&aacute;ximo 50 caracteres.',
        ],
        'responsable' => [
            'label'          => 'Responsable',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Nombre del responsable del almac&eacuten. M&aacute;ximo 50 caracteres.',
        ],
        'tipo_op' => [
            'label'          => 'Tipo operaci&oacute;n',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Seleccione el tipo de operaci&oacute;n.',
            'choices'        => [
                'MOVIL' => 'Operaci&oacute;n M&oacute;vil',
                'FIJA'  => 'Operaci&oacute;n Fija'
            ],
            'es_obligatorio' => true,
            'onchange'       => 'tipos',
        ],
        'tipos' => [
            'tipo'           => OrmModel::TIPO_HAS_MANY,
            'relation_model'         => TipoAlmacenSap::class,
            'relation_conditions'    => ['tipo_op' => '@field_value:tipo_op:MOVIL'],
            'texto_ayuda'    => 'Tipos asociados al almac&eacuten.',
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_almacenes_sap');
    }

    public function __toString()
    {
        return (string) $this->des_almacen;
    }

}
