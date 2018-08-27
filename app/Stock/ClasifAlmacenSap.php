<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class ClasifAlmacenSap extends OrmModel
{
    public $modelLabel = 'Clasificaci&oacute;n de Almac&eacute;n SAP';

    public $timestamps = false;
    protected $fillable = ['clasificacion', 'orden', 'dir_responsable', 'estado_ajuste', 'id_tipoclasif', 'tipo_op'];

    protected $guarded = [];

    protected $primaryKey = 'id_clasif';

    public $incrementing = true;

    public $modelFields = [
        'id_clasif' => [
            'tipo' => OrmField::TIPO_ID,
        ],
        'clasificacion' => [
            'label' => 'Clasificaci&oacute;n de Almac&eacute;n',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Clasificaci&oacute;n del almac&eacute;n. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
        ],
        'orden' => [
            'label' => 'Orden de la clasificaci&oacute;n',
            'tipo' => OrmField::TIPO_INT,
            'largo' => 10,
            'textoAyuda' => 'Orden de la clasificaci&oacute;n del almac&eacute;n.',
            'esObligatorio' => true,
        ],
        'dir_responsable' => [
            'label' => 'Direcci&oacute;n responsable',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 20,
            'textoAyuda' => 'Seleccione la direcci&oacute;n responsable',
            'choices' => [
                '*' => 'Por material',
                'TERMINALES' => 'Terminales',
                'REDES' => 'Redes',
                'EMPRESAS' => 'Empresas',
                'LOGISTICA' => 'Log&iacute;stica',
                'TTPP' => 'Telefon&iacute;a P&uacute;blica',
                'MARKETING' => 'Marketing',
            ],
            'esObligatorio' => true,
        ],
        'estado_ajuste' => [
            'label' => 'Estado de ajuste materiales',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 20,
            'textoAyuda' => 'Indica confiabilidad de existencia del material.',
            'choices' => [
                'EXISTE' => 'Existe',
                'NO_EXISTE' => 'No existe',
                'NO_SABEMOS' => 'No sabemos',
            ],
            'esObligatorio' => true,
        ],
        'id_tipoclasif' => [
            'tipo' =>  OrmField::TIPO_HAS_ONE,
            'relationModel' => TipoClasifAlmacenSap::class,
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
            'onchange' => 'tiposalm',
        ],
        'tipoAlmacenSap' => [
            'tipo' => OrmField::TIPO_HAS_MANY,
            'relationModel' => TipoAlmacenSap::class,
            'relationConditions' => array('tipo_op' => '@field_value:tipo_op:MOVIL'),
            'textoAyuda' => 'Tipos de almac&eacute;n asociados a la clasificaci&oacute;n.',
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
        return $this->belongsToMany(
            TipoAlmacenSap::class,
            config('invfija.bd_clasif_tipoalm_sap'),
            'id_clasif',
            'id_tipo'
        );
    }
}
