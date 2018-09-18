<?php

namespace App\Toa;

use App\Stock\TipoAlmacenSap;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['id_empresa', 'empresa'];
    protected $primaryKey = 'id_empresa';
    public $incrementing = false;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_empresas_toa');
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
