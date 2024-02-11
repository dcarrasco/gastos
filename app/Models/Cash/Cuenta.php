<?php

namespace App\Models\Cash;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cash\Cuenta
 *
 * @property int $id
 * @property string $nombre
 * @property string $codigo
 * @property string $descripcion
 * @property string $tipo_cuenta
 * @property string $moneda
 * @property string $color
 * @property int $limite_superior
 * @property int $limite_inferior
 * @property bool $contenedor
 * @property bool $oculto
 * @property bool $cuenta_superior
 */
class Cuenta extends Model
{
    use HasFactory;

    protected $table = "cash_cuentas";

    protected $fillable = ['nombre', 'codigo', 'descripcion', 'tipo_cuenta', 'moneda', 'color',
        'limite_superior', 'limite_inferior',
        'contenedor', 'oculto', 'cuenta_superior_id',
    ];

    /**
     * @return BelongsTo<Cuenta, Cuenta>
     */
    public function cuentaSuperior(): BelongsTo
    {
        return $this->belongsTo(Cuenta::class);
    }
}
