<?php

namespace App\OrmModel\Gastos;

use App\OrmModel\src\OrmField\BelongsTo;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\Resource;
use Illuminate\Http\Request;

class TipoGasto extends Resource
{
    public string $model = \App\Models\Gastos\TipoGasto::class;

    public string $label = 'Tipo de Gasto';

    public string $labelPlural = 'Tipos de Gasto';

    public string $icono = 'sitemap';

    public string $title = 'tipo_gasto';

    public array $search = [
        'id', 'tipo_gasto',
    ];

    public $orderBy = [
        'tipo_movimiento_id' => 'asc',
        'tipo_gasto' => 'asc',
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
