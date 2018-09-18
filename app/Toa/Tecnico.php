<?php

namespace App\Toa;

use App\Toa\Ciudad;
use App\Toa\Empresa;
use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    protected $fillable = ['id_tecnico', 'tecnico', 'rut', 'id_empresa', 'id_ciudad'];
    protected $primaryKey = 'id_tecnico';
    public $incrementing = false;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tecnicos_toa');
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
