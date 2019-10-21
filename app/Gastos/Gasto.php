<?php

namespace App\Gastos;

use Carbon\Carbon;
use App\Acl\Usuario;
use App\Gastos\Cuenta;
use App\Gastos\TipoGasto;
use App\Gastos\TipoMovimiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $table = 'cta_gastos';

    protected $fillable = [
        'cuenta_id', 'anno', 'mes', 'fecha', 'glosa', 'serie', 'tipo_gasto_id', 'monto', 'tipo_movimiento_id', 'usuario_id',
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

    public function getValorMontoAttribute()
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

    public static function movimientosMes($cuentaId, $anno, $mes)
    {
        $movimientos = static::with('tipoGasto', 'tipoMovimiento')
            ->cuentaAnnoMes($cuentaId, $anno, $mes)
            ->latest('fecha')->latest('id')
            ->get();

        $saldoMes = SaldoMes::getSaldoMesAnterior($cuentaId, $anno, $mes) + $movimientos->map->valor_monto->sum();

        return $movimientos->map(function($gasto) use (&$saldoMes) {
            return [
                'movimiento' => $gasto,
                'saldoInicial' => $saldoMes = $saldoMes - $gasto->valor_monto,
            ];
        });
    }

    public static function detalleMovimientosMes($cuentaId, $anno, $mes, $tipoGastoId)
    {
        return static::with('tipoGasto', 'tipoMovimiento')
            ->cuentaAnnoMes($cuentaId, $anno, $mes)
            ->whereTipoGastoId($tipoGastoId)
            ->orderBy('fecha')->orderBy('id')
            ->get();
    }

    public function movimientosAnno($cuentaId, $anno)
    {
        return $this->with('tipoMovimiento')
            ->where('cuenta_id', $cuentaId)
            ->where('anno', $anno)
            ->noSaldos()
            ->orderBy('fecha')
            ->get();
    }

    public function saldos($cuentaId, $anno)
    {
        $tipoMovimientoSaldo = 4;

        return $this->cuentaAnnoTipMov($cuentaId, $anno, $tipoMovimientoSaldo)
            ->orderBy('fecha')
            ->get();
    }

    public static function totalMes($cuentaId, $anno, $mes)
    {
        return static::movimientosMes($cuentaId, $anno, $mes)
            ->map(function($movimiento) {
                return $movimiento['movimiento']->valor_monto;
            })
            ->sum();
    }

    protected function getDataReporte($cuentaId, $anno, $tipoMovimientoId)
    {
        return static::cuentaAnnoTipMov($cuentaId, $anno, $tipoMovimientoId)
            ->select(DB::raw('mes, tipo_gasto_id, sum(monto) as sum_monto'))
            ->groupBy(['mes', 'tipo_gasto_id'])
            ->with('tipoGasto')
            ->get();
    }
}
