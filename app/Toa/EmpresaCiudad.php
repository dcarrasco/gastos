<?php

namespace App\Toa;

use App\Stock\AlmacenSap;
use Illuminate\Database\Eloquent\Model;

class EmpresaCiudad extends Model
{
    protected $fillable = ['id_empresa', 'id_ciudad'];
    protected $primaryKey = 'id_empresa';
    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_empresas_ciudades_toa');
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
