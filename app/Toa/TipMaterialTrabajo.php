<?php

namespace App\Toa;

use App\OrmModel\OrmModel;
use App\Inventario\Catalogo;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\HasMany;

class TipMaterialTrabajo extends OrmModel
{
    // Eloquent
    public $label = 'Tipo de Material Trabajo TOA';
    protected $fillable = ['desc_tip_material', 'color'];

    // OrmModel
    public $title = 'desc_tip_material';
    public $search = [
        'id', 'desc_tip_material', 'color',
    ];
    public $modelOrder = 'id';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tip_material_trabajo_toa');
    }

    public function fields()
    {
        return [
            Id::make()->sortable(),

            Text::make('descripcion', 'desc_tip_material')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Text::make('color')
                ->sortable()
                ->rules('max:20'),

            HasMany::make('catalogo'),
        ];
    }

    public function catalogo()
    {
        return $this->belongsToMany(
            Catalogo::class,
            config('invfija.bd_catalogo_tip_material_toa'),
            'id_tip_material_trabajo',
            'id_catalogo'
        );
    }
}
