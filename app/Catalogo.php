<?php

namespace App;

class Catalogo extends OrmModel
{
    public $modelLabel = 'Catalogo';

    protected $fillable = [
        'codigo', 'tipo', 'nombre',
    ];

    protected $guarded = [];

    protected $primaryKey = 'catalogo';
    public $incrementing = false;

    public $modelFields = [
        'catalogo' => [
            'label'          => 'Cat&aacute;logo',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 20,
            'texto_ayuda'    => 'C&oacute;digo del cat&aacute;logo. M&aacute;ximo 20 caracteres',
            'es_id'          => true,
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
        'descripcion' => [
            'label'          => 'Descripci&oacute;n del material',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Descripci&oacute;n del material. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            //'es_unico'       => true
        ],
        'pmp' => [
            'label'          => 'Precio Medio Ponderado (PMP)',
            'tipo'           => OrmModel::TIPO_REAL,
            'largo'          => 10,
            'decimales'      => 2,
            'texto_ayuda'    => 'Valor PMP del material',
            'es_obligatorio' => true,
            'es_unico'       => false,
            'formato'        => 'monto,1',
        ],
        'es_seriado' => [
            'label'          => 'Material seriado',
            'tipo'           => OrmModel::TIPO_BOOLEAN,
            'texto_ayuda'    => 'Indica si el material est&aacute; seriado en el sistema.',
            'es_obligatorio' => true,
            'default'        => 0
        ],
        // 'tip_material' => [
        //     'tipo'                   => OrmModel::TIPO_HAS_MANY,
        //     'relation_model'         => 'Tip_material_trabajo_toa',
        //     'relation_join_table'    => config('invfija.bd_catalogo_tip_material_toa'),
        //     'relation_id_one_table'  => ['id_catalogo'],
        //     'relation_id_many_table' => ['id_tip_material_trabajo'],
        //     //'relation_conditions'    => ['id_app' => '@field_value:id_app'],
        //     'texto_ayuda'    => 'Tipo de material TOA.',
        // ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_catalogos');
    }

    public function __toString()
    {
        return $this->descripcion;
    }
}
