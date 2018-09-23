<?php

namespace App\OrmModel\Gastos;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;

class TipoGasto extends Resource
{
    public $model = 'App\Gastos\TipoGasto';
    public $label = 'Tipo de Gasto';
    public $icono = 'sitemap';
    public $title = 'tipo_gasto';
    public $search = [
        'id', 'tipo_gasto'
    ];

    public $orderBy = 'tipo_gasto';

    public function fields(Request $request)
    {
        return [
            Id::make()->sortable(),

            Text::make('Tipo gasto')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }
}
