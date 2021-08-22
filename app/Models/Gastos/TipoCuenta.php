<?php

namespace App\Models\Gastos;

use App\Models\Gastos\Cuenta;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Model\Gastos\TipoCuenta
 * @property int $id
 * @property string $tipoCuenta
 * @property int $tipo
 * @property Collection $cuentas
 */
class TipoCuenta extends Model
{
    use HasFactory;

    protected $table = 'cta_tipos_cuentas';

    protected $fillable = ['tipo_movimiento_id', 'tipo_cuenta', 'tipo'];

    public const CUENTA_GASTO = 1;
    public const CUENTA_INVERSION = 2;

    public function cuentas(): HasMany
    {
        return $this->hasMany(Cuenta::class);
    }
}
