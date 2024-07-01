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
 *
 * @mixin \Illuminate\Database\Eloquent\Builder<Movimiento>
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
        'monto' => 'integer',
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
    public function contraCuenta(): BelongsTo
    {
        return $this->belongsTo(Cuenta::class, "contracuenta_id");
    }


    /**
     * @return Collection<array-key, Movimiento>
     */
    public static function showCuenta(Cuenta $cuenta): Collection
    {
        $total = 0;

        return static::where('cuenta_id', $cuenta->id)
            ->orderBy('fecha', 'asc')
            ->orderBy('id', 'asc')
            ->with('cuenta', 'cuenta.tipoCuenta', 'contraCuenta')
            ->get()
            ->each(function($movimiento) use (& $total) {
                $total += $movimiento->monto * $movimiento->signoCargo();
                $movimiento->balance = $total;
            });

    }

    public function signoCargo(): int
    {
        return $this->tipo_cargo == "C"
            ? $this->cuenta->tipoCuenta->signo_cargo
            : $this->cuenta->tipoCuenta->signo_abono;
    }


    public function getContraCargo(): string {
        return $this->tipo_cargo == "A" ? "C" : "A";
    }


    public function makeContraMovimiento(): static
    {
        $atributos = array_merge($this->toArray(), [
            'cuenta_id' => $this->contracuenta_id,
            'contracuenta_id' => $this->cuenta_id,
            'tipo_cargo' => $this->getContraCargo(),
        ]);

        return new static($atributos);
    }


    /**
     * @return array<string, string|int>
     */
    public static function initAttrib(): array
    {
        return [
            'movimiento_id' => Str::uuid()->toString(),
            'conciliado' => "n",
            'balance' => 0,
        ];
    }


    public function getContraMovimiento(): Movimiento
    {
        return Movimiento::where('movimiento_id', $this->movimiento_id)
            ->where('id', '<>', $this->id)
            ->first();
    }


    /**
     * @param array<string, string|int> $attributes
     */
    public function fillContraMovimiento(array $attributes, Movimiento $movimiento): Movimiento
    {
        $contraMovimientoValidated = array_merge($attributes, [
            'cuenta_id' => $attributes['contracuenta_id'],
            'contracuenta_id' => $attributes['cuenta_id'],
            'tipo_cargo' => $movimiento->getContraCargo(),
        ]);

        $this->fill($contraMovimientoValidated);

        return $this;

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

    public function getCargo(): int
    {
        return $this->tipo_cargo == "C" ? $this->monto : 0;
    }

    public function getIngreso(): int
    {
        return $this->tipo_cargo == "A" ? $this->monto : 0;
    }
}
