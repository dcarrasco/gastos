<?php

namespace App\Models\Gastos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * App\Models\Gastos\Cuenta
 *
 * @property int $id
 * @property int $banco_id
 * @property int $tipo_cuenta_id
 * @property string $cuenta
 */
class Cuenta extends Model
{
    use HasFactory;

    protected $table = 'cta_cuentas';

    protected $fillable = ['banco_id', 'tipo_cuenta_id', 'cuenta'];

    /**
     * @return BelongsTo<Banco, Cuenta>
     */
    public function banco(): BelongsTo
    {
        return $this->belongsTo(Banco::class);
    }

    /**
     * @return BelongsTo<TipoCuenta, Cuenta>
     */
    public function tipoCuenta(): BelongsTo
    {
        return $this->belongsTo(TipoCuenta::class);
    }

    /**
     * @return Collection<array-key, string>
     */
    protected static function selectOptions(int $tipo = 0): Collection
    {
        return TipoCuenta::where('tipo', $tipo)->with('cuentas')
            ->get()
            ->flatMap->cuentas
            ->sortBy('cuenta')
            ->pluck('cuenta', 'id');
    }

    /**
     * @return Collection<array-key, string>
     */
    public static function selectCuentasGastos(): Collection
    {
        return static::selectOptions(TipoCuenta::CUENTA_GASTO);
    }

    /**
     * @return Collection<array-key, string>
     */
    public static function selectCuentasInversiones(): Collection
    {
        return static::selectOptions(TipoCuenta::CUENTA_INVERSION);
    }
}
