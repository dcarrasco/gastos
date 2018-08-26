<?php

namespace App\Inventario;

use App\Acl\Usuario;
use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;
use App\Inventario\UploadDetalleInventario;

class DetalleInventario extends OrmModel
{
    use UploadDetalleInventario;

    public $modelLabel = 'Detalle Inventario';

    public $timestamps = true;
    protected $fillable = [
        'id_inventario',
        'hoja',
        'ubicacion',
        'hu',
        'catalogo',
        'descripcion',
        'lote',
        'centro',
        'almacen',
        'um',
        'stock_sap',
        'digitador',
        'stock_fisico',
        'auditor',
        'reg_nuevo',
        'observacion',
        'fecha_modificacion',
        'stock_ajuste',
        'glosa_ajuste'
    ];

    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'id_inventario' => 'integer',
        'hoja' => 'integer',
        'digitador' => 'integer',
        'auditor' => 'integer',
        'stock_sap' => 'integer',
        'stock_fisico' => 'integer',
        'stock_ajuste' => 'integer',
        // 'fecha_modificacion' => 'datetime',
        // 'fecha_ajuste' => 'datetime',
    ];

    public $modelFields = [
        'id' => [
            'tipo' => OrmField::TIPO_ID,
        ],
        'id_inventario' => [
            'tipo' => OrmField::TIPO_HAS_ONE,
            'relationModel' => Inventario::class,
        ],
        'hoja' => [
            'label' => 'Hoja',
            'tipo' => OrmField::TIPO_INT,
            'largo' => 10,
            'textoAyuda' => 'N&uacute;mero de la hoja usada en el inventario',
            'esObligatorio' => true,
        ],
        'ubicacion' => [
            'label' => 'Ubicaci&oacute;n del material',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 10,
            'textoAyuda' => 'Indica la posici&oacute;n del material en el almac&eacute;n.',
            'esObligatorio' => true,
        ],
        'hu' => [
            'label' => 'HU del material',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 20,
            'textoAyuda' => 'Indica la HU del material en el almac&eacute;n.',
            'esObligatorio' => false,
        ],
        'catalogo' => [
            'tipo' => OrmField::TIPO_HAS_ONE,
            'relationModel' => Catalogo::class,
            'textoAyuda' => 'Cat&aacute;logo del material.',
        ],
        'descripcion' => [
            'label' => 'Descripci&oacute;n del material',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 45,
            'textoAyuda' => 'M&aacute;ximo 45 caracteres.',
            'esObligatorio' => true,
        ],
        'lote' => [
            'label' => 'Lote del material',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 10,
            'textoAyuda' => 'Lote del material.',
            'esObligatorio' => true,
        ],
        'centro' => [
            'tipo' => OrmField::TIPO_HAS_ONE,
            'relationModel' => Centro::class,
        ],
        'almacen' => [
            'tipo' =>  OrmField::TIPO_HAS_ONE,
            'relationModel' => Almacen::class,
        ],
        'um' => [
            'tipo' =>  OrmField::TIPO_HAS_ONE,
            'relationModel' => UnidadMedida::class,
        ],
        'stock_sap' => [
            'label' => 'Stock SAP del material',
            'tipo' => OrmField::TIPO_INT,
            'largo' => 10,
            'textoAyuda' => 'Stock sist&eacute;mico (SAP) del material.',
            'esObligatorio' => true,
        ],
        'stock_fisico' => [
            'label' => 'Stock f&iacute;sico del material',
            'tipo' => OrmField::TIPO_INT,
            'largo' => 10,
            'textoAyuda' => 'Stock f&iacute;sico (inventariado) del material.',
            'esObligatorio' => true,
        ],
        'digitador' => [
            'tipo' => OrmField::TIPO_HAS_ONE,
            'relationModel' => Usuario::class,
            'textoAyuda' => 'Digitador de la hoja.',
        ],
        'auditor' => [
            'tipo' => OrmField::TIPO_HAS_ONE,
            'relationModel' => Auditor::class,
            'relationConditions' => ['activo' => 1],
            'textoAyuda' => 'Auditor de la hoja.',
        ],
        'reg_nuevo' => [
            'label' => 'Registro nuevo',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 1,
            'textoAyuda' => 'Indica si el registro es nuevo.',
            'esObligatorio' => true,
        ],
        'fecha_modificacion' => [
            'label' => 'Fecha de modificacion',
            'tipo' => OrmField::TIPO_DATETIME,
            'textoAyuda' => 'Fecha de modificaci&oacute;n del registro.',
            'esObligatorio' => true,
        ],
        'observacion' => [
            'label' => 'Observaci&oacute;n de registro',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 200,
            'textoAyuda' => 'M&aacute;ximo 200 caracteres.',
        ],
        'stock_ajuste' => [
            'label' => 'Stock de ajuste del material',
            'tipo' => OrmField::TIPO_INT,
            'largo' => 10,
            'textoAyuda' => 'M&aacute;ximo 100 caracteres.',
        ],
        'glosa_ajuste' => [
            'label' => 'Observaci&oacute;n del ajuste',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 100,
            'textoAyuda' => 'M&aacute;ximo 100 caracteres.',
        ],
        'fecha_ajuste' => [
            'label' => 'Fecha del ajuste',
            'tipo' => OrmField::TIPO_DATETIME,
            'textoAyuda' => 'Fecha de modificacion del ajuste.',
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

    // public function auditor()
    // {
    //     return $this->belongsTo(Auditor::class, 'auditor');
    // }

    public function __toString()
    {
        return (string) $this->hoja;
    }

    public function getStockAjusteAttribute($value)
    {
        return empty($value) ? 0 : $value;
    }

    public function editarLinea($request)
    {
        $this->fill($request->all());

        $this->id_inventario = Inventario::getInventarioActivo()->id;
        $this->descripcion = Catalogo::find($this->catalogo)->descripcion;
        $this->stock_sap = 0;
        $this->digitador = auth()->id();
        $this->auditor = Inventario::getInventarioActivo()->getDetalleHoja($request->input('hoja'))->first()->auditor;
        $this->reg_nuevo = 'S';
        $this->fecha_modificacion = \Carbon\Carbon::now();

        $this->save();
    }

}
