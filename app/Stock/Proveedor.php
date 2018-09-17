<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class Proveedor extends OrmModel
{
    // Eloquent
    protected $fillable = ['cod_proveedor', 'des_proveedor'];
    protected $primaryKey = 'cod_proveedor';
    public $incrementing = false;
    public $timestamps = false;

    // OrmModel
    public $title = 'des_proveedor';
    public $search = [
        'cod_proveedor', 'des_proveedor'
    ];
    public $modelOrder = 'des_proveedor';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_proveedores');
    }

    public function fields() {
        return [
            Text::make('codigo', 'cod_proveedor')
                ->sortable()
                ->rules('max:10', 'required', 'unique'),

            Text::make('descripcion','des_proveedor')
                ->sortable()
                ->rules('max:50', 'required'),
        ];
    }
}
