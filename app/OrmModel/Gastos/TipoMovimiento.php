<?php

namespace App\OrmModel\Gastos;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;

class TipoMovimiento extends Resource
{
    public $model = 'App\Gastos\TipoMovimiento';
    public $label = 'Tipo de Movimiento';
    public $icono = 'sitemap';
    public $title = 'tipo_movimiento';
    public $search = [
        'id', 'tipo_movimiento'
    ];

    public $orderBy = 'tipo_movimiento';

    public function fields(Request $request)
    {
        return [
            Id::make()->sortable(),

            Text::make('Tipo movimiento')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }
}
