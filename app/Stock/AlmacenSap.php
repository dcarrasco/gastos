<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class AlmacenSap extends OrmModel
{
    public $modelLabel = 'Almac&eacute;n SAP';

    protected $fillable = ['centro', 'cod_almacen', 'des_almacen', 'uso_almacen', 'responsable', 'tipo_op'];

    protected $guarded = [];

    public $incrementing = false;

    public $modelFields = [
        'centro' => [
            'label' => 'Centro',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 10,
            'textoAyuda' => 'C&oacute;digo SAP del centro. M&aacute;ximo 10 caracteres.',
            'esId' => true,
            'esObligatorio' => true,
        ],
        'cod_almacen' => [
            'label' => 'Almac&eacute;n',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 10,
            'textoAyuda' => 'C&oacute;digo SAP del almac&eacuten. M&aacute;ximo 10 caracteres.',
            'esId' => true,
            'esObligatorio' => true,
        ],
        'des_almacen' => [
            'label' => 'Descripci&oacute;n Almac&eacute;n',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Descripci&oacute;n del almac&eacuten. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
        ],
        'uso_almacen' => [
                'label' => 'Uso Almac&eacute;n',
                'tipo' => OrmField::TIPO_CHAR,
                'largo' => 50,
                'textoAyuda' => 'Indica para que se usa el almac&eacute;n. M&aacute;ximo 50 caracteres.',
        ],
        'responsable' => [
            'label' => 'Responsable',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Nombre del responsable del almac&eacuten. M&aacute;ximo 50 caracteres.',
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
            'onchange' => 'tipos',
        ],
        'tipos' => [
            'tipo' => OrmField::TIPO_HAS_MANY,
            'relationModel' => TipoAlmacenSap::class,
            'relationConditions' => ['tipo_op' => '@field_value:tipo_op:MOVIL'],
            'textoAyuda' => 'Tipos asociados al almac&eacuten.',
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_almacenes_sap');
    }

    public function __toString()
    {
        return (string) $this->centro.'-'.$this->cod_almacen.' '.$this->des_almacen;
    }

    public function tipos()
    {
        return $this->belongsToManyMultiKey(
            TipoAlmacenSap::class,
            config('invfija.bd_tipoalmacen_sap'),
            ['centro', 'cod_almacen'],
            'id_tipo'
        );
    }

    public static function getComboTiposOperacion($tipoOp = 'movil')
    {
        return models_array_options(
            self::where('tipo_op', $tipoOp)
                ->orderBy('centro')
                ->orderBy('cod_almacen')
                ->get()
        );
    }
}
