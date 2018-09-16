<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\BelongsTo;

class TipoUbicacion extends OrmModel
{
    // Eloquent
    protected $fillable = ['tipo_inventario', 'tipo_ubicacion'];

    // OrmModel
    public $label = 'Tipo de Ubicacion';
    public $title = 'tipo_ubicacion';
    public $search = [
        'id', 'tipo_ubicacion'
    ];
    public $modelOrder = 'tipo_inventario';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipo_ubicacion');
    }


    public function fields() {
        return [
            Id::make()->sortable(),

            BelongsTo::make('tipo inventario', 'tipoInventario')
                ->rules('required'),

            Text::make('tipo ubicacion')
                ->sortable()
                ->rules('max:30', 'required', 'unique'),
        ];
    }

    public function tipoInventario()
    {
        return $this->belongsTo(TipoInventario::class, 'tipo_inventario');
    }
}
