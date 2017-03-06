<?php

namespace App\Toa;

use App\OrmModel;

class TecnicoToa extends OrmModel
{
    public $modelLabel = 'T&eacute;cnico TOA';

    protected $fillable = ['id_tecnico', 'tecnico', 'rut', 'id_empresa', 'id_ciudad'];

    protected $guarded = [];

    protected $primaryKey = 'id_tecnico';
    public $incrementing = false;

    public $modelFields = [
        'id_tecnico' => [
            'label'          => 'ID T&eacute;cnico',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 20,
            'texto_ayuda'    => 'ID del t&eacute;cnico. M&aacute;ximo 20 caracteres.',
            'es_id'          => true,
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
        'tecnico' => [
            'label'          => 'Nombre t&eacute;cnico',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Nombre del t&eacute;cnico. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            // 'es_unico'       => true
        ],
        'rut' => [
            'label'          => 'RUT del t&eacute;cnico',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 20,
            'texto_ayuda'    => 'RUT del t&eacute;cnico. Sin puntos, con guion y d&iacute;gito verificador (en min&uacute;scula). M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            // 'es_unico'       => true
        ],
        'id_empresa' => [
            'tipo'           => OrmModel::TIPO_HAS_ONE,
            'relation_model' => EmpresaToa::class,
            'texto_ayuda'    => 'Empresa a la que pertenece el t&eacute;cnico.',
            // 'onchange'       => 'id_ciudad',
        ],
        'id_ciudad' => [
            'tipo'           => OrmModel::TIPO_HAS_ONE,
            'relation_model' => CiudadToa::class,
            'texto_ayuda'    => 'Ciudad a la que pertenece el t&eacute;cnico.',
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
        return fmt_rut($valor);
    }

    public function setRutAttribute($valor)
    {
        $this->attributes['rut'] = str_replace('.', '', $valor);
    }
}
