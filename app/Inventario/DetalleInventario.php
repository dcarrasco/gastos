<?php

namespace App\Inventario;

use App\OrmModel;
use App\Acl\Usuario;

class DetalleInventario extends OrmModel
{
    public $modelLabel = 'Detalle Inventario';

    protected $fillable = ['id_inventario', 'hoja', 'ubicacion', 'hu', 'catalogo', 'descripcion', 'lote', 'centro', 'almacen', 'um', 'stock_sap', 'digitador', 'stock_fisico', 'auditor', 'reg_nuevo', 'observacion', 'fecha_modificacion', 'stock_ajuste', 'glosa_ajuste'];

    protected $guarded = [];

    protected $casts = ['stock_ajuste' => 'int'];

    public $modelFields = [
        'id' => [
            'tipo'   => OrmModel::TIPO_ID,
        ],
        'id_inventario' => [
            'tipo'           => OrmModel::TIPO_HAS_ONE,
            'relation_model' => Inventario::class,
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
            'relation_model' => Catalogo::class,
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
            'tipo'           => OrmModel::TIPO_HAS_ONE,
            'relation_model' => Centro::class,
        ],
        'almacen' => [
            'tipo'           =>  OrmModel::TIPO_HAS_ONE,
            'relation_model' => Almacen::class,
        ],
        'um' => [
            'tipo'           =>  OrmModel::TIPO_HAS_ONE,
            'relation_model' => UnidadMedida::class,
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
            'relation_model' => Usuario::class,
            'texto_ayuda'    => 'Digitador de la hoja.',
        ],
        'auditor' => [
            'tipo'                => OrmModel::TIPO_HAS_ONE,
            'relation_model'      => Auditor::class,
            'relation-conditions' => ['activo' => 1],
            'texto_ayuda'         => 'Auditor de la hoja.',
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
            'label'       => 'Observaci&oacute;n de registro',
            'tipo'        => OrmModel::TIPO_CHAR,
            'largo'       => 200,
            'texto_ayuda' => 'M&aacute;ximo 200 caracteres.',
        ],
        'stock_ajuste' => [
            'label'       => 'Stock de ajuste del material',
            'tipo'        => OrmModel::TIPO_INT,
            'largo'       => 10,
            'texto_ayuda' => 'M&aacute;ximo 100 caracteres.',
        ],
        'glosa_ajuste' => [
            'label'       => 'Observaci&oacute;n del ajuste',
            'tipo'        => OrmModel::TIPO_CHAR,
            'largo'       => 100,
            'texto_ayuda' => 'M&aacute;ximo 100 caracteres.',
        ],
        'fecha_ajuste' => [
            'label'       => 'Fecha del ajuste',
            'tipo'        => OrmModel::TIPO_DATETIME,
            'texto_ayuda' => 'Fecha de modificacion del ajuste.',
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

    public function getStockAjusteAttribute($value)
    {
        return empty($value) ? 0 : $value;
    }
}
