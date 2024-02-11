<?php

namespace App\Models\Cash;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cash\Cuenta
 *
 * @property int $id
 * @property string $movimiento_id
 * @property int $cuenta_id
 * @property date $fecha
 * @property string $numero
 * @property string $descripcion
 * @property int $contracuenta_id
 * @property string $conciliado
 * @property string $tipo_cargo
 * @property int $monto
 * @property int $balance
 */
class Movimiento extends Model
{
    use HasFactory;

    protected $table = "cash_movimientos";

    protected $fillable = ['movimiento_id', 'cuenta_id', 'fecha', 'numero', 'descripcion',
        'contracuenta_id', 'conciliado', 'tipo_cargo', 'monto', 'balance',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    /**
     * @return BelongsTo<Cuenta, Movimiento>
     */
    public function cuenta(): BelongsTo
    {
        return $this->belongsTo(Cuenta::class);
    }

    /**
     * @return BelongsTo<Cuenta, Movimiento>
     */
    public function contracuenta(): BelongsTo
    {
        return $this->belongsTo(Cuenta::class, "contracuenta_id");
    }

}
