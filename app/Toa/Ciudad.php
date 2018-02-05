<?php

namespace App\Toa;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class Ciudad extends OrmModel
{
    public $modelLabel = 'Ciudad TOA';

    protected $fillable = ['id_ciudad', 'ciudad', 'orden'];

    protected $guarded = [];

    protected $primaryKey = 'id_ciudad';

    public $incrementing = false;

    public $modelFields = [
        'id_ciudad' => [
            'label' => 'ID de la ciudad',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 5,
            'textoAyuda' => 'ID de la ciudad. M&aacute;ximo 50 caracteres.',
            'esId' => true,
            'esObligatorio' => true,
            'esUnico' => true,
        ],
        'ciudad' => [
            'label' => 'Nombre de la ciudad',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Nombre de la ciudad. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
        ],
        'orden' => [
            'label' => 'Orden de la ciudad',
            'tipo' => OrmField::TIPO_INT,
            'textoAyuda' => 'Orden de despliegue de la ciudad.',
            'esObligatorio' => true,
        ],
    ];

    public $modelOrder = ['orden' => 'asc'];

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
