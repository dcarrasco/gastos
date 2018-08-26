<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class Catalogo extends OrmModel
{
    public $modelLabel = 'Catalogo';

    public $timestamps = true;
    protected $fillable = ['catalogo', 'descripcion', 'pmp', 'es_seriado'];

    protected $guarded = [];

    protected $primaryKey = 'catalogo';

    public $incrementing = false;

    public $modelFields = [
        'catalogo' => [
            'label' => 'Cat&aacute;logo',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 20,
            'textoAyuda' => 'C&oacute;digo del cat&aacute;logo. M&aacute;ximo 20 caracteres',
            'esId' => true,
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'descripcion' => [
            'label' => 'Descripci&oacute;n del material',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Descripci&oacute;n del material. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            //'esUnico' => true
        ],
        'pmp' => [
            'label' => 'Precio Medio Ponderado (PMP)',
            'tipo' => OrmField::TIPO_REAL,
            'largo' => 10,
            'decimales' => 2,
            'textoAyuda' => 'Valor PMP del material',
            'esObligatorio' => true,
            'esUnico' => false,
            'formato' => 'monto,1',
        ],
        'es_seriado' => [
            'label' => 'Material seriado',
            'tipo' => OrmField::TIPO_BOOLEAN,
            'textoAyuda' => 'Indica si el material est&aacute; seriado en el sistema.',
            'esObligatorio' => true,
            'default' => 0
        ],
        // 'tip_material' => [
        //     'tipo' => OrmField::TIPO_HAS_MANY,
        //     'relationModel' => 'Tip_material_trabajo_toa',
        //     'relation_join_table' => config('invfija.bd_catalogo_tip_material_toa'),
        //     'relation_id_one_table' => ['id_catalogo'],
        //     'relation_id_many_table' => ['id_tip_material_trabajo'],
        //     //'relationConditions' => ['id_app' => '@field_value:id_app'],
        //     'textoAyuda' => 'Tipo de material TOA.',
        // ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_catalogos');
    }

    public function __toString()
    {
        return (string) $this->descripcion;
    }
}
