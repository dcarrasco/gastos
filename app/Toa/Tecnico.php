<?php

namespace App\Toa;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class Tecnico extends OrmModel
{
    public $modelLabel = 'T&eacute;cnico TOA';

    protected $fillable = ['id_tecnico', 'tecnico', 'rut', 'id_empresa', 'id_ciudad'];

    protected $guarded = [];

    protected $primaryKey = 'id_tecnico';
    public $incrementing = false;

    public $modelFields = [
        'id_tecnico' => [
            'label' => 'ID T&eacute;cnico',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 20,
            'textoAyuda' => 'ID del t&eacute;cnico. M&aacute;ximo 20 caracteres.',
            'esId' => true,
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'tecnico' => [
            'label' => 'Nombre t&eacute;cnico',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Nombre del t&eacute;cnico. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            // 'esUnico' => true
        ],
        'rut' => [
            'label' => 'RUT del t&eacute;cnico',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 20,
            'textoAyuda' => 'RUT del t&eacute;cnico. '
                .'Sin puntos, con guion y d&iacute;gito verificador (en min&uacute;scula). '
                .'M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            // 'esUnico' => true
        ],
        'id_empresa' => [
            'tipo' => OrmField::TIPO_HAS_ONE,
            'relationModel' => Empresa::class,
            'textoAyuda' => 'Empresa a la que pertenece el t&eacute;cnico.',
            // 'onchange' => 'id_ciudad',
        ],
        'id_ciudad' => [
            'tipo' => OrmField::TIPO_HAS_ONE,
            'relationModel' => Ciudad::class,
            'textoAyuda' => 'Ciudad a la que pertenece el t&eacute;cnico.',
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tecnicos_toa');
    }

    public function __toString()
    {
        return (string) $this->tecnico;
    }

    public function empresaToa()
    {
        return $this->belongsTo(EmpresaToa::class, 'id_empresa');
    }

    public function ciudadToa()
    {
        return $this->belongsTo(CiudadToa::class, 'id_ciudad');
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
