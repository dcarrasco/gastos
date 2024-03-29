<?php

namespace App\Models\Gastos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * App\Models\Gastos\TipoMovimiento
 *
 * @property int $id
 * @property string $tipoMovimiento
 * @property int $signo
 * @property int $orden
 * @property Collection $tiposGastos
 */
class TipoMovimiento extends Model
{
    use HasFactory;

    protected $table = 'cta_tipos_movimientos';

    protected $fillable = ['tipo_movimiento', 'signo', 'orden'];

    /** @return HasMany<TipoGasto> */
    public function tiposGastos(): HasMany
    {
        return $this->hasMany(TipoGasto::class);
    }

    /** @return Collection<array-key, string> */
    public static function selectOptions(): Collection
    {
        return collect([0 => 'Todos'])
            ->merge(static::orderBy('orden')
                ->pluck('tipo_movimiento', 'id')
            );
    }
}
