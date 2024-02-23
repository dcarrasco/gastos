<?php

namespace App\Models\Cash;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
 * @property int $level
 * @property int $saldo
 */
class Cuenta extends Model
{
    use HasFactory;

    protected $table = "cash_cuentas";

    protected $fillable = ['nombre', 'codigo', 'descripcion', 'tipo_cuenta', 'moneda', 'color',
        'limite_superior', 'limite_inferior',
        'contenedor', 'oculto', 'cuenta_superior_id',
    ];

    protected $casts = [
        'contenedor' => 'boolean',
    ];

    /**
     * @return BelongsTo<TipoCuenta, Cuenta>
     */
    public function tipoCuenta(): BelongsTo
    {
        return $this->belongsTo(TipoCuenta::class, "tipo_cuenta");
    }

    /**
     * @return BelongsTo<Cuenta, Cuenta>
     */
    public function cuentaSuperior(): BelongsTo
    {
        return $this->belongsTo(Cuenta::class);
    }

    /**
     * @param Collection<array-key, Cuenta> $cuentas
     * @return Collection<array-key, Cuenta>
     */
    protected static function getSubCuentas(Collection $cuentas, int $cuenta_superior, int $level): Collection
    {
        $result = collect();

        $subcuentas = $cuentas
            ->filter(fn ($cuenta) => $cuenta->tipo_cuenta != 'root')
            ->filter(fn ($cuenta) => $cuenta->cuenta_superior_id == $cuenta_superior)
            ->sortBy("codigo");

        foreach ($subcuentas as $cuenta) {
            $cuenta->level = $level;
            $subsubcuentas = static::getSubCuentas($cuentas, $cuenta->id, $level + 1);

            $result->add($cuenta);
            foreach($subsubcuentas as $sub) {
                $result->add($sub);
            }
        }

        return $result;
    }

    /**
     * @return Collection<array-key, Cuenta>
     */
    public static function resumenCuentas(): Collection
    {
        $id_root = 1;
        $allCuentas = static::all();

        $cuentas = static::getSubCuentas($allCuentas, $id_root, 1)
            ->each(fn($cuenta) => $cuenta->saldo = Movimiento::showCuenta($cuenta)->last()?->balance);

        $maxNivel = $cuentas->filter(fn($cuenta) => $cuenta->contenedor)->pluck('level')->max();

        collect(range($maxNivel, 1))->each(fn($level) =>
            $cuentas->filter(fn($cuenta) => $cuenta->contenedor and $cuenta->level == $level)
                    ->pluck('id')
                    ->each(fn($id) => $cuentas->filter(fn($cuenta) => $cuenta->id == $id)
                        ->each(fn($cuenta) => $cuenta->saldo = $cuentas
                            ->filter(fn($cuenta) => $cuenta->cuenta_superior_id == $id)
                            ->pluck('saldo')->sum()
                        )
                    )
        );

        return $cuentas;
    }

    /**
     * @return Collection<array-key, string>
     */
    public static function selectCuentas(bool $showRoot = false): Collection
    {
        $id_root = 1;
        $formattedCuentas = collect($showRoot ? [$id_root => 'root'] : []);

        $cuentas = static::getSubCuentas(static::all(), $id_root, 1)->each(fn($cuenta) =>
            $formattedCuentas->put($cuenta->id, Str::repeat("--", $cuenta->level - 1) . " " . $cuenta->codigo . " " . $cuenta->nombre)
        );

        return $formattedCuentas;
    }
}
