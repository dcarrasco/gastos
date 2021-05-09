<?php

namespace App\Models\Gastos;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoGasto extends Model
{
    use HasFactory;

    protected $table = 'cta_tipos_gastos';

    protected $fillable = ['tipo_movimiento_id', 'tipo_gasto'];


    public function tipoMovimiento()
    {
        return $this->belongsTo(TipoMovimiento::class);
    }

    public static function selectOptions(): Collection
    {
        return TipoMovimiento::with('tiposGastos')->get()
            ->mapWithKeys(function ($elem) {
                return [$elem->tipo_movimiento => $elem];
            })
            ->map->tiposGastos
            ->filter(function ($elem) {
                return $elem->count() > 0;
            })
            ->map->pluck('tipo_gasto', 'id')
            ->map->sort()
            ->map->all();
    }
}
