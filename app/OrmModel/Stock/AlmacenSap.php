<?php

namespace App\OrmModel\Stock;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Select;

class AlmacenSap extends Resource
{
    public $model = 'App\Stock\AlmacenSap';
    public $label = 'Almacen SAP';
    public $icono = 'home';
    public $title = 'des_almacen';
    public $search = [
        'id_clasif', 'clasificacion'
    ];
    public $order = ['centro' => 'asc', 'cod_almacen' => 'asc'];

    public function fields(Request $request)
    {
        return [
            Text::make('centro')
                ->sortable()
                ->rules('max:10', 'required'),

            Text::make('cod almacen')
                ->sortable()
                ->rules('max:10', 'required'),

            Text::make('descripcion', 'des_almacen')
                ->sortable()
                ->rules('max:50', 'required'),

            Text::make('uso almacen')
                ->sortable()
                // ->hideFromIndex()
                ->rules('max:50', 'required'),

            Text::make('responsable')
                ->sortable()
                // ->hideFromIndex()
                ->rules('max:50', 'required'),

            Text::make('responsable')
                ->sortable()
                ->rules('max:50', 'required'),

            Select::make('tipo operacion', 'tipo_op')
                ->sortable()
                // ->hideFromIndex()
                ->options([
                    'MOVIL' => 'Operaci&oacute;n M&oacute;vil',
                    'FIJA' => 'Operaci&oacute;n Fija'
                ])
                ->rules('required'),
        ];
    }

    //     'tipos' => [
    //         'tipo' => OrmField::TIPO_HAS_MANY,
    //         'relationModel' => TipoAlmacenSap::class,
    //         'relationConditions' => ['tipo_op' => '@field_value:tipo_op:MOVIL'],
    //         'textoAyuda' => 'Tipos asociados al almac&eacuten.',
    //     ],
    // ];

}
