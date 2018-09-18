<?php

namespace App\Toa;

use App\Inventario\Catalogo;
use Illuminate\Database\Eloquent\Model;

class TipMaterialTrabajo extends Model
{
    protected $fillable = ['desc_tip_material', 'color'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tip_material_trabajo_toa');
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
