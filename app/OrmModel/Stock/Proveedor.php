<?php

namespace App\OrmModel\Stock;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Text;

class Proveedor extends Resource
{
    public $model = 'App\Stock\Proveedor';
    public $icono = 'shopping-cart';
    public $title = 'des_proveedor';
    public $search = [
        'cod_proveedor', 'des_proveedor'
    ];
    public $orderBy = 'des_proveedor';

    public function fields(Request $request)
    {
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
