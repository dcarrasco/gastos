<?php

namespace App\OrmModel\Gastos;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\OrmField\BelongsTo;

class TipoGasto extends Resource
{
    public $model = \App\Models\Gastos\TipoGasto::class;
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

    public function fields(Request $request): array
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
