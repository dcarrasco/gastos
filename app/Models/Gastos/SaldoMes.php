<?php

namespace App\Models\Gastos;

use Carbon\Carbon;
use App\Models\Gastos\Cuenta;
use Illuminate\Database\Eloquent\Model;

class SaldoMes extends Model
{
    protected $table = 'cta_saldos_mes';

    protected $fillable = [
        'cuenta_id', 'anno', 'mes', 'saldo_inicial', 'saldo_final',
    ];

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    public static function getSaldoMesAnterior(int $cuentaId, int $anno, int $mes): int
    {
        $fechaAnterior = Carbon::create($anno, $mes, 1)->subMonth();

        return static::firstOrNew([
            'cuenta_id' => $cuentaId,
            'anno' => $fechaAnterior->year,
            'mes' => $fechaAnterior->month,
        ])->saldo_final ?: 0;
    }

    public static function recalculaSaldoMes(int $cuentaId, int $anno, int $mes)
    {
        $saldoMes = static::firstOrNew([
            'cuenta_id' => $cuentaId,
            'anno' => $anno,
            'mes' => $mes,
        ]);

        $saldoMes->saldo_inicial = static::getSaldoMesAnterior($cuentaId, $anno, $mes);
        $saldoMes->saldo_final = $saldoMes->saldo_inicial + Gasto::totalMes($cuentaId, $anno, $mes);

        return $saldoMes->save();
    }
}
