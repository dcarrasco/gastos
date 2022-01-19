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
    public string $model = \App\Models\Gastos\TipoMovimiento::class;
    public string $label = 'Tipo de Movimiento';
    public string $labelPlural = 'Tipos de Movimiento';
    public string $icono = 'sitemap';
    public string $title = 'tipo_movimiento';
    public array $search = [
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

            Select::make('Signo')
                ->options([
                    '1' => 'Positivo',
                    '-1' => 'Negativo',
                ]),

            Number::make('Orden')
                ->rules('required'),
        ];
    }
}
