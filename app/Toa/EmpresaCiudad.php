<?php

namespace App\Toa;

use App\Stock\AlmacenSap;
use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class EmpresaCiudad extends OrmModel
{
    public $modelLabel = 'Empresa Ciudad TOA';

    protected $fillable = ['id_empresa', 'id_ciudad'];

    protected $guarded = [];

    // protected $primaryKey = 'id_tipo';
    // public $incrementing = false;
    public $modelFields = [
        'id_empresa' => [
            'tipo' => OrmField::TIPO_HAS_ONE,
            'esId' => true,
            'esObligatorio' => true,
            'relationModel' => EmpresaToa::class,
            'textoAyuda' => 'Seleccione una empresa TOA.',
        ],
        'id_ciudad' => [
            'tipo' => OrmField::TIPO_HAS_ONE,
            'esId' => true,
            'esObligatorio' => true,
            'relationModel' => CiudadToa::class,
            'textoAyuda' => 'Seleccione una Ciudad TOA.',
        ],
        'almacenes' => [
            'tipo' => OrmField::TIPO_HAS_MANY,
            'relationModel' => AlmacenSap::class,
            // 'join_table' => $this->config->item('bd_empresas_ciudades_almacenes_toa'),
            'id_one_table' => ['id_empresa', 'id_ciudad'],
            'id_many_table' => ['centro', 'cod_almacen'],
            'conditions' => ['centro' => ['CH32','CH33']],
            'textoAyuda' => 'Almacenes asociados a la empresa - ciudad.',
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_empresas_ciudades_toa');
    }

    public function __toString()
    {
        return (string) $this->desc_tipo;
    }

    public function empresaToa()
    {
        return $this->belongsTo(EmpresaToa::class, 'id_empresa');
    }

    public function ciudadToa()
    {
        return $this->belongsTo(CiudadToa::class, 'id_ciudad');
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
