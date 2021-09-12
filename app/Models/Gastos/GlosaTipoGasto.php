<?php

namespace App\Models\Gastos;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * App\Models\Gastos\GlosaTipoGasto
 * @property int $id
 * @property int $cuenta_id
 * @property int $tipo_gasto_id
 * @property string $glosa
 */
class GlosaTipoGasto extends Model
{
    protected $table = 'cta_glosa_tipo_gasto';

    protected $fillable = [
        'cuenta_id', 'glosa', 'tipo_gasto_id',
    ];


    public function cuenta(): BelongsTo
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function tipoGasto(): BelongsTo
    {
        return $this->belongsTo(TipoGasto::class);
    }

    public static function getCuenta(int $cuentaId): Collection
    {
        return static::with('tipoGasto', 'tipoGasto.tipoMovimiento')
            ->whereCuentaId($cuentaId)->get();
    }

    public function hasGlosa(string $glosa): bool
    {
        return Str::contains(strtoupper($glosa), strtoupper($this->glosa));
    }
}
