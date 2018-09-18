<?php

namespace App\OrmModel\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;
use App\OrmModel\OrmField\Select;
use App\OrmModel\OrmField\HasMany;
use App\OrmModel\OrmField\BelongsTo;

class ClasifAlmacenSap extends OrmModel
{
    public $model = 'App\Stock\ClasifAlmacenSap';
    public $title = 'tipo';
    public $search = [
        'id_clasif', 'clasificacion'
    ];
    public $modelOrder = 'id_clasif';

    public function fields() {
        return [
            Id::make('id clasif')->sortable(),

            Text::make('clasificacion')
                ->sortable()
                ->rules('max:50', 'required'),

            Number::make('orden')
                ->sortable()
                ->rules('required'),

            Select::make('dir responsable')
                ->sortable()
                ->options([
                    '*' => 'Por material',
                    'TERMINALES' => 'Terminales',
                    'REDES' => 'Redes',
                    'EMPRESAS' => 'Empresas',
                    'LOGISTICA' => 'Log&iacute;stica',
                    'TTPP' => 'Telefon&iacute;a P&uacute;blica',
                    'MARKETING' => 'Marketing',
                ])
                ->rules('required'),

            Select::make('estado ajuste')
                ->sortable()
                ->options([
                    'EXISTE' => 'Existe',
                    'NO_EXISTE' => 'No existe',
                    'NO_SABEMOS' => 'No sabemos',
                ])
                ->rules('required'),

            // BelongsTo::make('tipo clasificacion almacen', 'tipoClasifAlmacenSap'),

            Select::make('dir responsable')
                ->sortable()
                ->options([
                    'MOVIL' => 'Operaci&oacute;n M&oacute;vil',
                    'FIJA' => 'Operaci&oacute;n Fija'
                ])
                ->rules('required'),

            // HasMany::make('tipo almacen SAP', 'tipoAlmacenSap'),


        ];
    }

}
