<?php

namespace App\Toa;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\BelongsTo;

class Tecnico extends OrmModel
{
    // Eloquent
    protected $fillable = ['id_tecnico', 'tecnico', 'rut', 'id_empresa', 'id_ciudad'];
    protected $primaryKey = 'id_tecnico';
    public $incrementing = false;

    // OrmModel
    public $title = 'tecnico';
    public $search = [
        'id_tecnico', 'tecnico', 'rut',
    ];
    public $modelOrder = 'id_tecnico';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tecnicos_toa');
    }

    public function fields() {
        return [
            Text::make('id tecnico')
                ->sortable()
                ->rules('max:20', 'required', 'unique'),

            Text::make('tecnico')
                ->sortable()
                ->rules('max:50', 'required'),

            Text::make('tecnico')
                ->sortable()
                ->rules('max:50', 'required'),

            Text::make('rut')
                ->sortable()
                ->rules('max:20', 'required'),

            BelongsTo::make('empresa', 'empresaToa'),

            BelongsTo::make('ciudad', 'ciudadToa'),
        ];
    }


    public function empresaToa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function ciudadToa()
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad');
    }

    public function getRutAttribute($valor)
    {
        return fmtRut($valor);
    }

    public function setRutAttribute($valor)
    {
        $this->attributes['rut'] = str_replace('.', '', $valor);
    }
}
