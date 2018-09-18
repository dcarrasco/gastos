<?php

namespace App\Inventario;

use Illuminate\Database\Eloquent\Model;

class TipoInventario extends Model
{
    protected $fillable = ['id_tipo_inventario', 'desc_tipo_inventario'];
    protected $primaryKey = 'id_tipo_inventario';
    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipos_inventario');
    }
}
