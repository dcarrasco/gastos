<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;
use App\OrmModel\OrmField\Select;
use App\OrmModel\OrmField\Textarea;
use App\OrmModel\OrmField\BelongsTo;

class DetalleInventario extends Resource
{
    public $model = 'App\Inventario\DetalleInventario';
    public $label = 'Detalle de inventario';
    public $icono = 'search-plus';
    public $title = 'descripcion';
    public $search = [
        'hoja', 'ubicacion', 'descripcion'
    ];
    public $order = ['hoja' => 'asc', 'ubicacion' => 'asc'];

    public function fields(Request $request)
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('inventario', 'inventario', 'App\OrmModel\Inventario\Inventario')
                ->rules('required'),

            Number::make('hoja')
                ->sortable()
                ->rules('required'),

            Text::make('ubicacion')
                ->sortable()
                ->rules('max:10', 'required'),

            Text::make('hu')
                ->sortable()
                ->rules('max:20'),

            Text::make('catalogo')
                ->sortable()
                ->rules('max:20', 'required'),

            Text::make('descripcion')
                ->hideFromIndex()
                ->rules('max:40', 'required'),

            Text::make('lote')
                ->hideFromIndex()
                ->rules('max:10', 'required'),

            BelongsTo::make('centro', 'centroRelation', 'App\OrmModel\Inventario\Centro')
                ->hideFromIndex()
                ->rules('required'),

            BelongsTo::make('almacen', 'almacenRelation', 'App\OrmModel\Inventario\Almacen')
                ->hideFromIndex()
                ->rules('required'),

            BelongsTo::make('um', 'umRelation', 'App\OrmModel\Inventario\UnidadMedida')
                ->hideFromIndex()
                ->rules('required'),

            Number::make('stock sap')
                ->hideFromIndex()
                ->rules('required'),

            Number::make('stock fisico')
                ->sortable()
                ->rules('required'),

            BelongsTo::make('digitador', 'digitadorRelation', 'App\OrmModel\Acl\Usuario')
                ->hideFromIndex()
                ->rules('required'),

            BelongsTo::make('auditor', 'auditorRelation', 'App\OrmModel\Inventario\Auditor')
                ->hideFromIndex()
                ->rules('required'),

            Select::make('reg nuevo')
                ->hideFromIndex()
                ->options([
                    'S' => 'Si',
                    'N' => 'No',
                ])
                ->rules('required'),

            Textarea::make('observacion')
                ->hideFromIndex()
                ->rules('max:200'),

            Text::make('fecha modificacion')
                ->hideFromIndex(),

            Number::make('stock ajuste')
                ->sortable(),

            Textarea::make('glosa ajuste')
                ->hideFromIndex()
                ->rules('max:100'),

            Text::make('fecha ajuste')
                ->hideFromIndex(),
        ];
    }

}
