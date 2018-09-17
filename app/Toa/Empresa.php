<?php

namespace App\Toa;

use App\OrmModel\OrmModel;
use App\Stock\TipoAlmacenSap;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\HasMany;

class Empresa extends OrmModel
{
    // Eloquent
    protected $fillable = ['id_empresa', 'empresa'];
    protected $primaryKey = 'id_empresa';
    public $incrementing = false;

    // OrmModel
    public $title = 'empresa';
    public $search = [
        'id_empresa', 'empresa',
    ];
    public $modelOrder = 'empresa';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_empresas_toa');
    }

    public function fields()
    {
        return [
            Text::make('id empresa')
                ->sortable()
                ->rules('max:20', 'required', 'unique'),

            Text::make('empresa')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            HasMany::make('tipo almacen sap', 'tipoalmacensap'),

            HasMany::make('ciudad', 'ciudadToa'),
        ];
    }

    public function tipoAlmacenSap()
    {
        return $this->belongsToMany(
            TipoAlmacenSap::class,
            config('invfija.bd_empresas_toa_tiposalm'),
            'id_empresa',
            'id_tipo'
        );
    }

    public function ciudadToa()
    {
        return $this->belongsToMany(
            Ciudad::class,
            config('invfija.bd_empresas_ciudades_toa'),
            'id_empresa',
            'id_ciudad'
        );
    }
}
