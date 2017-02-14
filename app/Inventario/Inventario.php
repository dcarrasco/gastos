<?php

namespace App\Inventario;

use App\OrmModel;
use App\Inventario\ReportesInventario;
use App\Inventario\AjustesInventario;
use App\Inventario\DetalleInventario;

class Inventario extends OrmModel
{
    use ReportesInventario, AjustesInventario;

    public $modelLabel = 'Inventario';
    public $modelOrder = 'nombre';

    protected $fillable = ['nombre', 'activo', 'tipo_inventario'];

    protected $guarded = [];

    public $modelFields = [
        'id' => [
            'tipo'   => OrmModel::TIPO_ID,
        ],
        'nombre' => [
            'label'          => 'Nombre del inventario',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
        'activo' => [
            'label'          => 'Activo',
            'tipo'           => OrmModel::TIPO_BOOLEAN,
            'texto_ayuda'    => 'Indica se el inventario est&aacute; activo dentro del sistema.',
            'es_obligatorio' => true,
        ],
        'tipo_inventario' => [
            'tipo'           => OrmModel::TIPO_HAS_ONE,
            'relation_model' => TipoInventario::class,
            'texto_ayuda'    => 'Seleccione el tipo de inventario.',
            'es_obligatorio' => true,
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_inventarios');
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function tipoInventario()
    {
        return $this->belongsTo(TipoInventario::class, 'tipo_inventario');
    }

    public function lineas()
    {
        return $this->hasMany(DetalleInventario::class, 'id_inventario');
    }

    public static function getIDInventarioActivo()
    {
        return static::where('activo', '=', 1)->first()->getKey();
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
        return $this->lineas()->where('hoja', $hoja)->get();
    }
}
