<?php

namespace App\Stock;

use App\OrmModel;

class TipoAlmacenSap extends OrmModel
{
    public $modelLabel = 'Tipo Almac&eacute;n SAP';

    protected $fillable = [
        'centro', 'cod_almacen', 'des_almacen', 'uso_almace', 'icono',
    ];

    protected $guarded = [];

    protected $primaryKey = 'id_tipo';
    // public $incrementing = false;

    public $modelFields = [
        'id_tipo' => [
            'tipo' => OrmModel::TIPO_INT,
        ],
        'tipo' => [
            'label' => 'Tipo de Almac&eacute;n',
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 50,
            'texto_ayuda' => 'Tipo del almac&eacute;n. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
        ],
        'tipo_op' => [
            'label' => 'Tipo operaci&oacute;n',
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 50,
            'texto_ayuda' => 'Seleccione el tipo de operaci&oacute;n.',
            'choices' => [
                'MOVIL' => 'Operaci&oacute;n M&oacute;vil',
                'FIJA' => 'Operaci&oacute;n Fija'
            ],
            'es_obligatorio' => true,
            'onchange' => 'almacenes',
        ],
        'es_sumable' => [
            'label' => 'Es sumable',
            'tipo' => OrmModel::TIPO_BOOLEAN,
            'texto_ayuda' => 'Indica si el tipo de almac&eacute;n se incluir&aacute; en la suma del stock.',
            'es_obligatorio' => true,
            'default' => 1,
        ],
        'almacen' => [
            'tipo' => OrmModel::TIPO_HAS_MANY,
            'relation_model' => AlmacenSap::class,
            'relation_conditions' => ['tipo_op' => '@field_value:tipo_op:MOVIL'],
            'texto_ayuda' => 'Tipos asociados al almac&eacute;n.',
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tiposalm_sap');
    }

    public function __toString()
    {
        return (string) $this->tipo;
    }

    public function almacen()
    {
        return $this->belongsToMany(AlmacenSap::class, config('invfija.bd_tipoalmacen_sap'), 'id_tipo', 'id_modulo');
    }

    public static function getComboTiposOperacion($tipoOp = 'movil')
    {
        return models_array_options(
            self::where('tipo_op', $tipoOp)
                ->orderBy('tipo')
                ->get()
        );
    }
}
