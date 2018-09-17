<?php

namespace App\Toa;

use App\Stock\AlmacenSap;
use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\BelongsTo;

class EmpresaCiudad extends OrmModel
{
    // Eloquent
    public $label = 'Empresa Ciudad TOA';
    protected $fillable = ['id_empresa', 'id_ciudad'];
    protected $primaryKey = 'id_empresa';
    public $incrementing = false;

    // OrmModel
    public $title = 'empresa';
    public $search = [
        'id_empresa', 'empresa',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_empresas_ciudades_toa');
    }

    public function fields()
    {
        return [
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
}
