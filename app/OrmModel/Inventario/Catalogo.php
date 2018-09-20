<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;
use App\OrmModel\OrmField\Boolean;

class Catalogo extends Resource
{
    public $model = 'App\Inventario\Catalogo';
    public $title = 'descripcion';
    public $icono = 'barcode';
    public $search = [
        'catalogo', 'descripcion'
    ];
    public $orderBy = 'descripcion';

    public function fields(Request $request)
    {
        return [
            Text::make('catalogo')
                ->sortable()
                ->rules('max:20', 'required', 'unique'),

            Text::make('descripcion')
                ->sortable()
                ->rules('max:50', 'required'),

            Number::make('pmp')
                ->sortable()
                ->rules('required'),

            Boolean::make('es seriado')
                ->rules('required'),
        ];
    }
}
