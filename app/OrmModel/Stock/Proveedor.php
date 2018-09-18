<?php

namespace App\OrmModel\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class Proveedor extends OrmModel
{
    public $model = 'App\Stock\Proveedor';
    public $icono = 'shopping-cart';
    public $title = 'des_proveedor';
    public $search = [
        'cod_proveedor', 'des_proveedor'
    ];
    public $modelOrder = 'des_proveedor';

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
