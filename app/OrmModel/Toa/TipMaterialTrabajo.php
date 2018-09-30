<?php

namespace App\OrmModel\Toa;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\HasMany;

class TipMaterialTrabajo extends Resource
{
    public $model = 'App\Toa\TipMaterialTrabajo';
    public $icono = 'object-group';
    public $label = 'Tipo de Material Trabajo TOA';
    public $labelPlural = 'Tipos de Materiales Trabajo TOA';
    public $title = 'desc_tip_material';
    public $search = [
        'id', 'desc_tip_material', 'color',
    ];
    public $orderBy = 'id';

    public function fields(Request $request)
    {
        return [
            Id::make()->sortable(),

            Text::make('descripcion', 'desc_tip_material')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Text::make('color')
                ->sortable()
                ->rules('max:20'),

            HasMany::make('catalogo', 'catalogo', 'App\OrmModel\Inventario\Catalogo'),
        ];
    }
}
