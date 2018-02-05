<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

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
            'tipo' => OrmField::TIPO_INT,
        ],
        'tipo' => [
            'label' => 'Tipo de Almac&eacute;n',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Tipo del almac&eacute;n. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
        ],
        'tipo_op' => [
            'label' => 'Tipo operaci&oacute;n',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Seleccione el tipo de operaci&oacute;n.',
            'choices' => [
                'MOVIL' => 'Operaci&oacute;n M&oacute;vil',
                'FIJA' => 'Operaci&oacute;n Fija'
            ],
            'esObligatorio' => true,
            'onchange' => 'almacenes',
        ],
        'es_sumable' => [
            'label' => 'Es sumable',
            'tipo' => OrmField::TIPO_BOOLEAN,
            'textoAyuda' => 'Indica si el tipo de almac&eacute;n se incluir&aacute; en la suma del stock.',
            'esObligatorio' => true,
            'default' => 1,
        ],
        'almacen' => [
            'tipo' => OrmField::TIPO_HAS_MANY,
            'relationModel' => AlmacenSap::class,
            'relation_conditions' => ['tipo_op' => '@field_value:tipo_op:MOVIL'],
            'textoAyuda' => 'Tipos asociados al almac&eacute;n.',
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
