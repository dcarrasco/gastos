<?php

namespace App\Inventario;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $fillable = ['almacen'];
    protected $primaryKey = 'almacen';
    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_almacenes');
    }
}
