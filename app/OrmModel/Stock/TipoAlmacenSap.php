<?php

namespace App\OrmModel\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Select;
use App\OrmModel\OrmField\Boolean;
use App\OrmModel\OrmField\HasMany;

class TipoAlmacenSap extends OrmModel
{
    public $model = 'App\Stock\TipoAlmacenSap';
    public $title = 'tipo';
    public $search = [
        'tipo',
    ];
    public $modelOrder = 'id_tipo';

    public function fields() {
        return [
            Id::make('id tipo')->sortable(),

            Text::make('tipo')
                ->sortable()
                ->rules('max:50', 'required'),

            Select::make('tipo operacion', 'tipo_op')
                ->sortable()
                ->options([
                    'MOVIL' => 'Operaci&oacute;n M&oacute;vil',
                    'FIJA' => 'Operaci&oacute;n Fija'
                ])
                ->rules('required'),

            Boolean::make('es sumable')
                ->rules('required'),

            // HasMany::make('almacen'),
        ];
    }
    //     'almacen' => [
    //         'tipo' => OrmField::TIPO_HAS_MANY,
    //         'relationModel' => AlmacenSap::class,
    //         'relationConditions' => ['tipo_op' => '@field_value:tipo_op:MOVIL'],
    //         'textoAyuda' => 'Tipos asociados al almac&eacute;n.',
    //     ],
    // ];

}
