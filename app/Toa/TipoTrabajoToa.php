<?php

namespace App\Toa;

use App\OrmModel;

class TipoTrabajoToa extends OrmModel
{
    public $modelLabel = 'Tipo de Trabajo TOA';

    protected $fillable = [
        'desc_tipo',
    ];

    protected $guarded = [];

    protected $primaryKey = 'id_tipo';
    public $incrementing = false;

    public $modelFields = [
        'id_tipo' => [
            'label'          => 'Tipo de rabajo',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 30,
            'texto_ayuda'    => 'Tipo de trabajo. M&aacute;ximo 30 caracteres.',
            'es_id'          => true,
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
            'desc_tipo' => [
            'label'          => 'Descripci&oacute;n tipo de trabajo',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Descripci&oacute;n del tipo de trabajo. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipos_trabajo_toa');
    }

    public function __toString()
    {
        return (string) $this->desc_tipo;
    }
}
