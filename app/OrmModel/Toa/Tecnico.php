<?php

namespace App\OrmModel\Toa;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\BelongsTo;

class Tecnico extends Resource
{
    public $model = 'App\Toa\Tecnico';
    public $icono = 'user';
    public $title = 'tecnico';
    public $search = [
        'id_tecnico', 'tecnico', 'rut',
    ];
    public $orderBy = 'id_tecnico';

    public function fields(Request $request)
    {
        return [
            Text::make('ID', 'id_tecnico')
                ->sortable()
                ->rules('max:20', 'required', 'unique'),

            Text::make('Tecnico')
                ->sortable()
                ->rules('max:50', 'required'),

            Text::make('RUT tecnico', 'rut')
                ->sortable()
                ->rules('max:20', 'required'),

            BelongsTo::make('Empresa', 'empresaToa', 'App\OrmModel\Toa\Empresa'),

            BelongsTo::make('Ciudad', 'ciudadToa', 'App\OrmModel\Toa\Ciudad'),
        ];
    }
}
