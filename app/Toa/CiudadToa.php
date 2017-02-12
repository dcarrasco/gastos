<?php

namespace App\Toa;

use App\OrmModel;

class CiudadToa extends OrmModel
{
    public $modelLabel = 'Ciudad TOA';

    protected $fillable = [
        'tecnico', 'rut', 'id_empresa', 'id_ciudad'
    ];

    protected $guarded = [];

    protected $primaryKey = 'id_ciudad';
    public $incrementing = false;

    public $modelFields = [
        'id_ciudad' => [
            'label'          => 'ID de la ciudad',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 5,
            'texto_ayuda'    => 'ID de la ciudad. M&aacute;ximo 50 caracteres.',
            'es_id'          => true,
            'es_obligatorio' => true,
            'es_unico'       => true,
        ],
        'ciudad' => [
            'label'          => 'Nombre de la ciudad',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Nombre de la ciudad. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
        ],
        'orden' => [
            'label'          => 'Orden de la ciudad',
            'tipo'           => OrmModel::TIPO_INT,
            'texto_ayuda'    => 'Orden de despliegue de la ciudad.',
            'es_obligatorio' => true,
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_ciudades_toa');
    }

    public function __toString()
    {
        return (string) $this->ciudad;
    }
}
