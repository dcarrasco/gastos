<?php

namespace App\Inventario;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $fillable = ['unidad', 'desc_unidad'];
    protected $primaryKey = 'unidad';
    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_unidades');
    }
}
