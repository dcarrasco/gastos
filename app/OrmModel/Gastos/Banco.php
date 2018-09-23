<?php

namespace App\OrmModel\Gastos;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;

class Banco extends Resource
{
    public $model = 'App\Gastos\Banco';
    public $icono = 'university';
    public $title = 'nombre';
    public $search = [
        'id', 'nombre'
    ];

    public $orderBy = 'nombre';

    public function fields(Request $request)
    {
        return [
            Id::make()->sortable(),

            Text::make('Nombre')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }
}
