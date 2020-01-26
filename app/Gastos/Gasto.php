<?php

namespace App\Gastos;

use App\Acl\Usuario;
use App\Gastos\Cuenta;
use App\Gastos\TipoGasto;
use App\Gastos\TipoMovimiento;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class Gasto extends Model
{
    protected $table = 'cta_gastos';

    protected $fillable = [
        'cuenta_id', 'anno', 'mes', 'fecha', 'glosa', 'serie', 'tipo_gasto_id',
        'monto', 'tipo_movimiento_id', 'usuario_id',
    ];

    protected $dates = [
        'fecha'
    ];


    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function tipoGasto()
    {
        return $this->belongsTo(TipoGasto::class);
    }

    public function tipoMovimiento()
    {
        return $this->belongsTo(TipoMovimiento::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function getValorMontoAttribute(): int
    {
        return $this->monto * $this->tipoMovimiento->signo;
    }


    public function scopeCuentaAnnoMes($query, $cuentaId, $anno, $mes)
    {
        return $query->where('cuenta_id', $cuentaId)
            ->where('anno', $anno)
            ->where('mes', $mes);
    }

    public function scopeCuentaAnnoTipMov($query, $cuentaId, $anno, $tipoMovimientoId)
    {
        return $query->where('cuenta_id', $cuentaId)
            ->where('anno', $anno)
            ->where('tipo_movimiento_id', $tipoMovimientoId);
    }

    public function scopeNoSaldos($query)
    {
        $tipoMovimientoSaldo = 4;

        return $query->where('tipo_movimiento_id', '<>', $tipoMovimientoSaldo); // excluye movimientos de saldos
    }

    public static function movimientosMes($cuentaId, $anno, $mes): Collection
    {
        $movimientos = static::with('tipoGasto', 'tipoMovimiento')
            ->cuentaAnnoMes($cuentaId, $anno, $mes)
            ->latest('fecha')->latest('id')
            ->get();

        $saldoMes = SaldoMes::getSaldoMesAnterior($cuentaId, $anno, $mes) + $movimientos->map->valor_monto->sum();

        return $movimientos->map(function ($gasto) use (&$saldoMes) {
            $saldoMes = $saldoMes - $gasto->valor_monto;
            $gasto->saldo_inicial = $saldoMes;
            $gasto->saldo_final = $saldoMes + $gasto->valor_monto;

            return $gasto;
        });
    }

    public static function detalleMovimientosMes($cuentaId, $anno, $mes, $tipoGastoId): EloquentCollection
    {
        return static::with('tipoGasto', 'tipoMovimiento')
            ->cuentaAnnoMes($cuentaId, $anno, $mes)
            ->whereTipoGastoId($tipoGastoId)
            ->orderBy('fecha')->orderBy('id')
            ->get();
    }

    public static function movimientosAnno($cuentaId, $anno): EloquentCollection
    {
        return static::with('tipoMovimiento')
            ->where('cuenta_id', $cuentaId)
            ->where('anno', $anno)
            ->noSaldos()
            ->orderBy('fecha')
            ->get();
    }

    public static function saldos($cuentaId, $anno): EloquentCollection
    {
        $tipoMovimientoSaldo = 4;

        return static::cuentaAnnoTipMov($cuentaId, $anno, $tipoMovimientoSaldo)
            ->orderBy('fecha')
            ->get();
    }

    public static function totalMes($cuentaId, $anno, $mes): int
    {
        return static::movimientosMes($cuentaId, $anno, $mes)
            ->map->valor_monto
            ->sum();
    }

    protected function getDataReporte($cuentaId, $anno, $tipoMovimientoId): EloquentCollection
    {
        return static::cuentaAnnoTipMov($cuentaId, $anno, $tipoMovimientoId)
            ->select(DB::raw('mes, tipo_gasto_id, sum(monto) as sum_monto'))
            ->groupBy(['mes', 'tipo_gasto_id'])
            ->with('tipoGasto')
            ->get();
    }
}
