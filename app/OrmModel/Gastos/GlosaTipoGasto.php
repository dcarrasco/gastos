<?php

namespace App\OrmModel\Gastos;

use App\OrmModel\src\OrmField\BelongsTo;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\Resource;
use Illuminate\Http\Request;

class GlosaTipoGasto extends Resource
{
    public string $model = \App\Models\Gastos\GlosaTipoGasto::class;

    public string $label = 'Glosa Tipo de Gasto';

    public string $labelPlural = 'Glosas Tipos de Gasto';

    public string $icono = 'sitemap';

    public string $title = 'glosa';

    public array $search = [
        'id', 'glosa',
    ];

    protected $paginationLinksDetail = true;

    public $orderBy = 'glosa';

    public function fields(Request $request): array
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('Cuenta', 'cuenta', Cuenta::class)
                ->rules('required'),

            Text::make('Glosa')
                ->sortable()
                ->rules('max:200', 'required', 'unique'),

            BelongsTo::make('Tipo de Gasto', 'tipoGasto', TipoGasto::class)
                ->rules('required'),
        ];
    }
}
