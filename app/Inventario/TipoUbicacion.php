<?php

namespace App\Inventario;

use App\Inventario\TipoInventario;
use Illuminate\Database\Eloquent\Model;

class TipoUbicacion extends Model
{
    protected $fillable = ['tipo_inventario', 'tipo_ubicacion'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipo_ubicacion');
    }

    public function tipoInventario()
    {
        return $this->belongsTo(TipoInventario::class, 'tipo_inventario');
    }
}
