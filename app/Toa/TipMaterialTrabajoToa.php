<?php

namespace App\Toa;

use App\OrmModel;
use App\Inventario\Catalogo;

class TipMaterialTrabajoToa extends OrmModel
{
    public $modelLabel = 'Tipo de Material Trabajo TOA';

    protected $fillable = ['desc_tip_material', 'color'];

    protected $guarded = [];

    // protected $primaryKey = 'id_tipo';
    // public $incrementing = false;

    public $modelFields = [
        'id' => [
            'tipo' => OrmModel::TIPO_ID,
        ],
        'desc_tip_material' => [
            'label' => 'Descripci&oacute;n tipo de material',
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 50,
            'texto_ayuda' => 'Nombre del tipo de material. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico' => true
        ],
        'color' => [
            'label' => 'Color tipo material',
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 20,
            'texto_ayuda' => 'Color o clase que identifica el tipo de material. M&aacute;ximo 50 caracteres.',
        ],
        'catalogo' => [
            'tipo' => OrmModel::TIPO_HAS_MANY,
            'relation_model' => Catalogo::class,
            'texto_ayuda' => 'Tipo de material TOA.',
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
        return $this->belongsToMany(
            Catalogo::class,
            config('invfija.bd_catalogo_tip_material_toa'),
            'id_tip_material_trabajo',
            'id_catalogo'
        );
    }
}
