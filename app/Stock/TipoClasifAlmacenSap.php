<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;

class TipoClasifAlmacenSap extends OrmModel
{
    // Eloquent
    protected $fillable = ['tipo', 'color'];
    protected $primaryKey = 'id_tipoclasif';
    public $timestamps = false;

    // OrmModel
    public $label = 'Tipo Clasificacion de Almacen SAP';
    public $title = 'tipo';
    public $search = [
        'id_tipoclasif', 'tipo', 'color'
    ];
    public $modelOrder = 'id_tipoclasif';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipo_clasifalm_sap');
    }

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
