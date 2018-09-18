<?php

namespace App\OrmModel\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;

class TipoClasifAlmacenSap extends OrmModel
{
    public $model = 'App\Stock\TipoClasifAlmacenSap';
    public $label = 'Tipo Clasificacion de Almacen SAP';
    public $title = 'tipo';
    public $search = [
        'id_tipoclasif', 'tipo', 'color'
    ];
    public $modelOrder = 'id_tipoclasif';

    public function fields() {
        return [
            Id::make('id', 'id_tipoclasif')->sortable(),

            Text::make('tipo')
                ->sortable()
                ->rules('max:50', 'required'),

            Text::make('color')
                ->sortable()
                ->rules('max:50'),
        ];
    }
}
