<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Boolean;
use App\OrmModel\OrmField\BelongsTo;
use App\Inventario\AjustesInventario;
use App\Inventario\DetalleInventario;
use App\Inventario\ReportesInventario;

class Inventario extends OrmModel
{
    use AjustesInventario;

    // Eloquent
    protected $fillable = ['nombre', 'activo', 'tipo_inventario'];
    protected $casts = [
        'id' => 'integer',
        'activo' => 'boolean',
    ];

    // OrmModel
    public $title = 'nombre';
    public $search = [
        'id', 'nombre',
    ];
    public $modelOrder = 'nombre';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_inventarios');
    }


    public function fields() {
        return [
            Id::make()->sortable(),

            Text::make('nombre')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Boolean::make('activo')
                ->sortable()
                ->rules('required'),

            BelongsTo::make('tipo inventario', 'tipoInventario')
                ->rules('required'),
        ];
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
