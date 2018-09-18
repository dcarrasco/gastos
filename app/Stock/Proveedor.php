<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $fillable = ['cod_proveedor', 'des_proveedor'];
    protected $primaryKey = 'cod_proveedor';
    public $incrementing = false;
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_proveedores');
    }

}
