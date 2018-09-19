<?php

namespace App\Inventario;

use App\Acl\Usuario;
use Illuminate\Database\Eloquent\Model;
use App\Inventario\UploadDetalleInventario;

class DetalleInventario extends Model
{
    use UploadDetalleInventario;

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


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_detalle_inventario');
    }

    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'id_inventario');
    }

    public function centroRelation()
    {
        return $this->belongsTo(Centro::class, 'centro', 'centro');
    }

    public function almacenRelation()
    {
        return $this->belongsTo(Almacen::class, 'almacen');
    }

    public function umRelation()
    {
        return $this->belongsTo(UnidadMedida::class, 'um');
    }

    public function digitadorRelation()
    {
        return $this->belongsTo(Usuario::class, 'digitador');
    }

    public function auditorRelation()
    {
        return $this->belongsTo(Auditor::class, 'auditor');
    }

    // public function auditor()
    // {
    //     return $this->belongsTo(Auditor::class, 'auditor');
    // }

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
