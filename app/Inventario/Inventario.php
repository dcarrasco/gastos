<?php

namespace App\Inventario;

use App\Inventario\TipoInventario;
use App\Inventario\AjustesInventario;
use App\Inventario\DetalleInventario;
use App\Inventario\ReportesInventario;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use AjustesInventario;

    protected $fillable = ['nombre', 'activo', 'tipo_inventario'];
    protected $casts = [
        'id' => 'integer',
        'activo' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_inventarios');
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
