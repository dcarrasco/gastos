<?php

namespace App;

class TipMaterialTrabajoToa extends OrmModel
{
    public $modelLabel = 'Tipo de Material Trabajo TOA';

    protected $fillable = [
        'desc_tip_material', 'color'
    ];

    protected $guarded = [];

    // protected $primaryKey = 'id_tipo';
    // public $incrementing = false;

    public $modelFields = [
        'id' => [
            'tipo'           => OrmModel::TIPO_ID,
        ],
        'desc_tip_material' => [
            'label'          => 'Descripci&oacute;n tipo de material',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Nombre del tipo de material. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
        'color' => [
            'label'          => 'Color tipo material',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 20,
            'texto_ayuda'    => 'Color o clase que identifica el tipo de material. M&aacute;ximo 50 caracteres.',
        ],
        'tip_material' => [
            'tipo'           => OrmModel::TIPO_HAS_MANY,
            'relation_model' => 'catalogo',
            // 'join_table'    => $this->config->item('bd_catalogo_tip_material_toa'),
            'id_one_table' => array('id_tip_material_trabajo'),
            'id_many_table'  => array('id_catalogo'),
            'texto_ayuda'    => 'Tipo de material TOA.',
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tip_material_trabajo_toa');
    }

    public function __toString()
    {
        return (string) $this->desc_tipo;
    }

    public function catalogo()
    {
        return $this->belongsToMany(Catalogo::class, config('invfija.bd_catalogo_tip_material_toa'), 'id_tip_material_trabajo', 'id_catalogo');
    }
}
