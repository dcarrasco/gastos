<?php

namespace App\OrmModel\Gastos;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\BelongsTo;

class GlosaTipoGasto extends Resource
{
    public $model = 'App\Gastos\GlosaTipoGasto';
    public $icono = 'sitemap';
    public $title = 'glosa';
    public $search = [
        'id', 'glosa',
    ];

    public $orderBy = 'glosa';

    public function fields(Request $request)
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('Cuenta', 'cuenta', Cuenta::class)
                ->rules('required'),

            Text::make('Glosa')->sortable()->rules('max:200', 'required', 'unique'),

            BelongsTo::make('Tipo de Gasto', 'tipoGasto', TipoGasto::class)
                ->rules('required'),
        ];
    }
}
