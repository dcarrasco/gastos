<?php

namespace App;

class ClasifAlmacenSap extends OrmModel
{
    public $modelLabel = 'Clasificaci&oacute;n de Almac&eacute;n SAP';

    protected $fillable = [
        'centro', 'cod_almacen', 'des_almacen', 'uso_almace', 'icono',
    ];

    protected $guarded = [];

    protected $primaryKey = 'id_clasif';
    // public $incrementing = false;

    public $modelFields = [
        'id_clasif' => [
            'tipo'             => OrmModel::TIPO_INT,
        ],
        'clasificacion' => [
            'label'          => 'Clasificaci&oacute;n de Almac&eacute;n',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Clasificaci&oacute;n del almac&eacute;n. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
        ],
        'orden' => [
            'label'          => 'Orden de la clasificaci&oacute;n',
            'tipo'           => OrmModel::TIPO_INT,
            'largo'          => 10,
            'texto_ayuda'    => 'Orden de la clasificaci&oacute;n del almac&eacute;n.',
            'es_obligatorio' => true,
        ],
        'dir_responsable' => [
            'label'          => 'Direcci&oacute;n responsable',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 20,
            'texto_ayuda'    => 'Seleccione la direcci&oacute;n responsable',
            'choices'        => [
                '*'          => 'Por material',
                'TERMINALES' => 'Terminales',
                'REDES'      => 'Redes',
                'EMPRESAS'   => 'Empresas',
                'LOGISTICA'  => 'Log&iacute;stica',
                'TTPP'       => 'Telefon&iacute;a P&uacute;blica',
                'MARKETING'  => 'Marketing',
            ],
            'es_obligatorio' => true,
        ],
        'estado_ajuste' => [
            'label'          => 'Estado de ajuste materiales',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 20,
            'texto_ayuda'    => 'Indica confiabilidad de existencia del material.',
            'choices'        => [
                'EXISTE'     => 'Existe',
                'NO_EXISTE'  => 'No existe',
                'NO_SABEMOS' => 'No sabemos',
            ],
            'es_obligatorio' => true,
        ],
        'tipoClasifAlmacenSap' => [
            'tipo'           =>  OrmModel::TIPO_HAS_ONE,
            'relation_model' => 'tipoClasifAlmacenSap',
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
            'onchange'       => 'tiposalm',
        ],
        'tipoAlmacenSap' => [
            'tipo'                => OrmModel::TIPO_HAS_MANY,
            'relation_model'      => 'tipoAlmacenSap',
            'relation_conditions' => array('tipo_op' => '@field_value:tipo_op:MOVIL'),
            'texto_ayuda'         => 'Tipos de almac&eacute;n asociados a la clasificaci&oacute;n.',
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_clasifalm_sap');
    }

    public function __toString()
    {
        return (string) $this->tipo;
    }

    public function tipoClasifAlmacenSap()
    {
        return $this->belongsTo(TipoClasifAlmacenSap::class, 'id_tipoclasif');
    }

    public function tipoAlmacenSap()
    {
        return $this->belongsToMany(TipoAlmacenSap::class, config('invfija.bd_clasif_tipoalm_sap'), 'id_clasif', 'id_tipo');
    }

}
