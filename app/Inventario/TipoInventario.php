<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class TipoInventario extends OrmModel
{
    // Eloquent
    protected $fillable = ['id_tipo_inventario', 'desc_tipo_inventario'];
    protected $primaryKey = 'id_tipo_inventario';
    public $incrementing = false;

    // OrmModel
    public $label = 'Tipo de inventario';
    public $title = 'desc_tipo_inventario';
    public $search = [
        'desc_tipo_inventario'
    ];
    public $modelOrder = ['id_tipo_inventario' => 'asc'];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipos_inventario');
    }

    public function fields() {
        return [
            Text::make('id tipo inventario')
                ->sortable()
                ->rules('max:10', 'required', 'unique'),

            Text::make('desc tipo inventario')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }
}
