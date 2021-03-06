<?php

namespace App\OrmModel\Gastos;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;

class Banco extends Resource
{
    public $model = 'App\Models\Gastos\Banco';
    public $icono = 'university';
    public $title = 'nombre';
    public $search = [
        'id', 'nombre'
    ];

    public $orderBy = 'nombre';

    public function fields(Request $request): array
    {
        return [
            Id::make()->sortable(),

            Text::make('Nombre')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }
}
