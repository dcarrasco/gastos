<?php

namespace App\OrmModel\Gastos;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\OrmField\Number;
use App\OrmModel\src\OrmField\Select;

class TipoMovimiento extends Resource
{
    public $model = 'App\Models\Gastos\TipoMovimiento';
    public $label = 'Tipo de Movimiento';
    public $labelPlural = 'Tipos de Movimiento';
    public $icono = 'sitemap';
    public $title = 'tipo_movimiento';
    public $search = [
        'id', 'tipo_movimiento'
    ];

    public $orderBy = 'tipo_movimiento';

    public function fields(Request $request): array
    {
        return [
            Id::make()->sortable(),

            Text::make('Tipo movimiento')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Select::make('Signo')->options([
                '1' => 'Positivo',
                '-1' => 'Negativo',
            ]),

            Number::make('Orden')->rules('required'),
        ];
    }
}
