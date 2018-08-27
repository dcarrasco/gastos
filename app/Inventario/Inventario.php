<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;
use App\Inventario\ReportesInventario;
use App\Inventario\AjustesInventario;
use App\Inventario\DetalleInventario;

class Inventario extends OrmModel
{
    use AjustesInventario;

    public $modelLabel = 'Inventario';

    public $modelOrder = 'nombre';

    protected $fillable = ['nombre', 'activo', 'tipo_inventario'];

    protected $casts = [
        'id' => 'integer',
        'activo' => 'boolean',
    ];

    protected $guarded = [];

    public $modelFields = [
        'id' => [
            'tipo' => OrmField::TIPO_ID,
        ],
        'nombre' => [
            'label' => 'Nombre del inventario',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'activo' => [
            'label' => 'Activo',
            'tipo' => OrmField::TIPO_BOOLEAN,
            'textoAyuda' => 'Indica se el inventario est&aacute; activo dentro del sistema.',
            'esObligatorio' => true,
        ],
        'tipo_inventario' => [
            'tipo' => OrmField::TIPO_HAS_ONE,
            'relationModel' => TipoInventario::class,
            'textoAyuda' => 'Seleccione el tipo de inventario.',
            'esObligatorio' => true,
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_inventarios');
    }

    public function __toString()
    {
        return (string) $this->nombre;
    }

    public function tipoInventario()
    {
        return $this->belongsTo(TipoInventario::class, 'tipo_inventario');
    }

    public function lineas()
    {
        return $this->hasMany(DetalleInventario::class, 'id_inventario');
    }

    public static function getIdInventarioActivo()
    {
        return static::where('activo', 1)->first()->getKey();
    }

    public static function getInventarioActivo()
    {
        return static::find(static::getIDInventarioActivo());
    }

    public function getMaxHoja()
    {
        return $this->lineas()->max('hoja');
    }

    public function getDetalleHoja($hoja = null)
    {
        return $this->lineas()->where('hoja', $hoja)->orderBy('ubicacion')->get();
    }

    public function updateDetalleHoja($detalle = [], $auditor = null)
    {
        return collect($detalle)->each(function ($datos, $id) use ($detalle, $auditor) {
            $datos = array_merge($datos, [
                'auditor' => $auditor,
                'digitador' => auth()->id(),
                'fecha_modificacion' => \Carbon\Carbon::now(),
            ]);

            $lineaDetalle = DetalleInventario::find($id)->update($datos);
        })
        ->count();
    }
}
