<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;

class ClasifAlmacenSap extends Model
{
    protected $fillable = ['clasificacion', 'orden', 'dir_responsable', 'estado_ajuste', 'id_tipoclasif', 'tipo_op'];
    protected $primaryKey = 'id_clasif';
    public $timestamps = false;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_clasifalm_sap');
    }

    public function tipoClasifAlmacenSap()
    {
        return $this->belongsTo(TipoClasifAlmacenSap::class, 'id_tipoclasif');
    }

    public function tipoAlmacenSap()
    {
        return $this->belongsToMany(
            TipoAlmacenSap::class,
            config('invfija.bd_clasif_tipoalm_sap'),
            'id_clasif',
            'id_tipo'
        );
    }
}
