<?php

namespace App\OrmModel\Gastos;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\BelongsTo;

class TipoGasto extends Resource
{
    public $model = 'App\Gastos\TipoGasto';
    public $label = 'Tipo de Gasto';
    public $labelPlural = 'Tipos de Gasto';
    public $icono = 'sitemap';
    public $title = 'tipo_gasto';
    public $search = [
        'id', 'tipo_gasto'
    ];

    public $orderBy = [
        'tipo_movimiento_id' => 'asc',
        'tipo_gasto' => 'asc'
    ];

    public function fields(Request $request)
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('Tipo Movimiento', 'tipoMovimiento', TipoMovimiento::class),

            Text::make('Tipo gasto')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }
}
