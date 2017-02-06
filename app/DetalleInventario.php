<?php

namespace App;

class DetalleInventario extends OrmModel
{
    public $modelLabel = 'Detalle Inventario';

    protected $fillable = [
        'ubicacion', 'catalogo', 'lote', 'centro', 'almacen', 'um', 'stock_fisico', 'auditor', 'observacion'
    ];

    protected $guarded = [];

    public $modelFields = [
        'id' => [
            'tipo'   => OrmModel::TIPO_ID,
        ],
        'id_inventario' => [
            'tipo'           => OrmModel::TIPO_HAS_ONE,
            'relation_model' => 'inventario',
        ],
        'hoja' => [
            'label'          => 'Hoja',
            'tipo'           => OrmModel::TIPO_INT,
            'largo'          => 10,
            'texto_ayuda'    => 'N&uacute;mero de la hoja usada en el inventario',
            'es_obligatorio' => true,
        ],
        'ubicacion' => [
            'label'          => 'Ubicaci&oacute;n del material',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 10,
            'texto_ayuda'    => 'Indica la posici&oacute;n del material en el almac&eacute;n.',
            'es_obligatorio' => true,
        ],
        'hu' => [
            'label'          => 'HU del material',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 20,
            'texto_ayuda'    => 'Indica la HU del material en el almac&eacute;n.',
            'es_obligatorio' => false,
        ],
        'catalogo' => [
            'tipo'           => OrmModel::TIPO_HAS_ONE,
            'relation_model' => 'catalogo',
            'texto_ayuda'    => 'Cat&aacute;logo del material.',
        ],
        'descripcion' => [
            'label'          => 'Descripci&oacute;n del material',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 45,
            'texto_ayuda'    => 'M&aacute;ximo 45 caracteres.',
            'es_obligatorio' => true,
        ],
        'lote' => [
            'label'          => 'Lote del material',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 10,
            'texto_ayuda'    => 'Lote del material.',
            'es_obligatorio' => true,
        ],
        'centro' => [
            'tipo'           =>  OrmModel::TIPO_HAS_ONE,
            'relation_model' => 'centro'
        ],
        'almacen' => [
            'tipo'           =>  OrmModel::TIPO_HAS_ONE,
            'relation_model' => 'almacen'
        ],
        'um' => [
            'tipo'           =>  OrmModel::TIPO_HAS_ONE,
            'relation_model' => 'unidadMedida'
        ],
        'stock_sap' => [
            'label'          => 'Stock SAP del material',
            'tipo'           => OrmModel::TIPO_INT,
            'largo'          => 10,
            'texto_ayuda'    => 'Stock sist&eacute;mico (SAP) del material.',
            'es_obligatorio' => true,
        ],
        'stock_fisico' => [
            'label'          => 'Stock f&iacute;sico del material',
            'tipo'           => OrmModel::TIPO_INT,
            'largo'          => 10,
            'texto_ayuda'    => 'Stock f&iacute;sico (inventariado) del material.',
            'es_obligatorio' => true,
        ],
        'digitador' => [
            'tipo'           => OrmModel::TIPO_HAS_ONE,
            'relation_model' => 'usuario',
            'texto_ayuda'    => 'Digitador de la hoja.',
        ],
        'auditor' => [
            'tipo'           => OrmModel::TIPO_HAS_ONE,
            'relation_model'      => 'auditor',
            'relation-conditions' => ['activo' => 1],
            'texto_ayuda'    => 'Auditor de la hoja.',
        ],
        'reg_nuevo' => [
            'label'          => 'Registro nuevo',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 1,
            'texto_ayuda'    => 'Indica si el registro es nuevo.',
            'es_obligatorio' => true,
        ],
        'fecha_modificacion' => [
            'label'          => 'Fecha de modificacion',
            'tipo'           => OrmModel::TIPO_DATETIME,
            'texto_ayuda'    => 'Fecha de modificaci&oacute;n del registro.',
            'es_obligatorio' => true,
        ],
        'observacion' => [
            'label'          => 'Observaci&oacute;n de registro',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 200,
            'texto_ayuda'    => 'M&aacute;ximo 200 caracteres.',
        ],
        'stock_ajuste' => [
            'label'          => 'Stock de ajuste del material',
            'tipo'           => OrmModel::TIPO_INT,
            'largo'          => 10,
            'texto_ayuda'    => 'M&aacute;ximo 100 caracteres.',
        ],
        'glosa_ajuste' => [
            'label'          => 'Observaci&oacute;n del ajuste',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 100,
            'texto_ayuda'    => 'M&aacute;ximo 100 caracteres.',
        ],
        'fecha_ajuste' => [
            'label'          => 'Fecha del ajuste',
            'tipo'           => OrmModel::TIPO_DATETIME,
            'texto_ayuda'    => 'Fecha de modificacion del ajuste.',
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_detalle_inventario');
    }

    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'id_inventario');
    }

    public function auditor()
    {
        return $this->belongsTo(Auditor::class, 'auditor');
    }

    public function __toString()
    {
        return $this->hoja;
    }

    public function getIngresoInventarioValidation()
    {
        $validation = $this->getValidation();
        unset($validation['id_inventario']);
        unset($validation['hoja']);
        unset($validation['descripcion']);
        unset($validation['stock_sap']);
        unset($validation['digitador']);
        unset($validation['auditor']);
        unset($validation['reg_nuevo']);
        unset($validation['fecha_modificacion']);

        return $validation;
    }

}
