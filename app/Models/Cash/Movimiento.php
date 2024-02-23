<?php

namespace App\Models\Cash;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public static function showCuenta(Cuenta $cuenta): Collection
    {
        $movimientos = static::where('cuenta_id', $cuenta->id)
            ->orderBy('fecha', 'asc')
            ->orderBy('id', 'asc')
            ->with('cuenta', 'cuenta.tipoCuenta')
            ->get();

        $total = 0;
        foreach ($movimientos as $mov) {
            $signo = $mov->tipo_cargo == "C"
                ? $mov->cuenta->tipoCuenta->signo_cargo
                : $mov->cuenta->tipoCuenta->signo_abono;

            $total += $mov->monto * $signo;
            $mov->balance = $total;
        }
        return $movimientos;
    }

    public static function getUUID(): string
    {
        return Str::uuid();
    }

    public function contraMovimiento(): Movimiento
    {
        return Movimiento::where('movimiento_id', $this->movimiento_id)
            ->where('id', '<>', $this->id)
            ->first();
    }

    /**
     * @return array<string, string>
     */
    public static function selectTiposCargo(Cuenta $cuenta): array
    {
        return [
            'C' => $cuenta->tipoCuenta->nombre_cargo,
            'A' => $cuenta->tipoCuenta->nombre_abono,
        ];
    }

    public function getCargo(): string
    {
        return $this->tipo_cargo == "C" ? $this->monto : "";
    }

    public function getIngreso(): string
    {
        return $this->tipo_cargo == "A" ? $this->monto : "";
    }
}
