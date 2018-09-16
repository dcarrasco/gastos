<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class UnidadMedida extends OrmModel
{
    // Eloquent
    protected $fillable = ['unidad', 'desc_unidad'];
    protected $primaryKey = 'unidad';
    public $incrementing = false;

    // OrmModel
    public $title = 'desc_unidad';
    public $search = [
        'centro'
    ];
    public $modelOrder = 'desc_unidad';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_unidades');
    }

    public function fields() {
        return [
            Text::make('unidad')
                ->sortable()
                ->rules('max:10', 'required', 'unique'),

            Text::make('descripcion', 'desc_unidad')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }
}
