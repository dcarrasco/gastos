<?php

namespace App\Inventario;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    protected $fillable = ['codigo', 'tipo', 'nombre'];
    protected $primaryKey = 'codigo';
    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_familias');
    }
}
