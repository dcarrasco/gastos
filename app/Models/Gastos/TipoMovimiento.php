<?php

namespace App\Models\Gastos;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Gastos\TipoMovimiento
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


    public function tiposGastos(): HasMany
    {
        return $this->hasMany(TipoGasto::class);
    }

    public static function selectOptions(): Collection
    {
        return static::orderBy('orden')
            ->pluck('tipo_movimiento', 'id');
    }
}
