<?php

namespace App\Models\Gastos;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Gastos\TipoGasto
 * @property int     $id
 * @property string  $tipoGasto
 * @property TipoMovimiento $tipoMovimiento
 * @property int     $tipo_movimiento_id
 */
class TipoGasto extends Model
{
    use HasFactory;

    protected $table = 'cta_tipos_gastos';

    protected $fillable = ['tipo_movimiento_id', 'tipo_gasto'];


    /** @return BelongsTo<TipoMovimiento, TipoGasto> */
    public function tipoMovimiento(): BelongsTo
    {
        return $this->belongsTo(TipoMovimiento::class);
    }

    /** @return Collection<int, string> */
    public static function selectOptions(): Collection
    {
        return TipoMovimiento::with('tiposGastos')->get()
            ->mapWithKeys(fn($elem) => [$elem->tipo_movimiento => $elem])
            ->map->tiposGastos
            ->filter(fn($tipoGasto) => $tipoGasto->isNotEmpty())
            ->map->pluck('tipo_gasto', 'id')
            ->map->sort()
            ->map->all();
    }
}
