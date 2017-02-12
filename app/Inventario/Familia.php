<?php

namespace App\Inventario;

use App\OrmModel;

class Familia extends OrmModel
{
    public $modelLabel = 'Familia';

    protected $fillable = [
        'codigo', 'tipo', 'nombre',
    ];

    protected $guarded = [];

    protected $primaryKey = 'codigo';

    public $modelFields = [
        'codigo' => [
            'label'          => 'C&oacute;digo de la familia',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'M&aacute;ximo 50 caracteres.',
            'es_id'          => true,
            'es_obligatorio' => true,
            'es_unico'       => true,
        ],
        'tipo' => [
            'label'          => 'Tipo de familia',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 30,
            'texto_ayuda'    => 'Seleccione el tipo de familia.',
            'choices'        => [
                'FAM' => 'Familia',
                'SUBFAM' => 'SubFamilia'
            ],
            'es_obligatorio' => true,
        ],
        'nombre' => [
            'label'          => 'Nombre de la familia',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico'       => true,
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_familias');
    }

    public function __toString()
    {
        return $this->nombre;
    }
}
