<?php

namespace App\Gastos;

use Carbon\Carbon;
use App\Acl\Usuario;
use App\Gastos\Cuenta;
use App\Gastos\TipoGasto;
use Illuminate\Http\Request;
use App\Gastos\TipoMovimiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $fillable = [
        'cuenta_id', 'anno', 'mes', 'fecha', 'glosa', 'serie', 'tipo_gasto_id', 'monto', 'tipo_movimiento_id', 'usuario_id',
    ];

    protected $dates = [
        'fecha'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'cta_gastos';
    }

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
        return static::with('tipoGasto', 'tipoMovimiento')
            ->cuentaAnnoMes($cuentaId, $anno, $mes)
            ->latest('fecha')->latest('id')
            ->get();
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
        return static::movimientosMes($cuentaId, $anno, $mes)->map(function($gasto) {
            return $gasto->monto * $gasto->tipoMovimiento->signo;
        })->sum();
    }

    protected static function dataReporte($cuentaId, $anno, $tipoMovimientoId)
    {
        return static::cuentaAnnoTipMov($cuentaId, $anno, $tipoMovimientoId)
            ->select(DB::raw('mes, tipo_gasto_id, sum(monto) as sum_monto'))
            ->groupBy(['mes', 'tipo_gasto_id'])
            ->get();
    }

    protected static function sumDataReporte($cuentaId, $anno, $tipoMovimientoId, string $campo)
    {
        return static::cuentaAnnoTipMov($cuentaId, $anno, $tipoMovimientoId)
            ->select(DB::raw($campo.', sum(monto) as sum_monto'))
            ->groupBy([$campo])
            ->pluck('sum_monto', $campo);
    }

    public static function getReporte($cuentaId, $anno, $tipoMovimientoId)
    {
        $data = static::dataReporte($cuentaId, $anno, $tipoMovimientoId);
        $tipo_gasto_id = $data->pluck('tipo_gasto_id')->unique()->all();
        $tiposGasto = TipoGasto::nombresTipoGastos($tipo_gasto_id);

        $datos = collect($tipo_gasto_id)->combine($tipo_gasto_id)
            ->map(function ($tipo_gasto_id) use ($data) {
                return $data->where('tipo_gasto_id', $tipo_gasto_id)->pluck('sum_monto', 'mes')->all();
            });

        $meses = Cuenta::getFormMes('M');
        $sum_tipo_gasto = static::sumDataReporte($cuentaId, $anno, $tipoMovimientoId, 'tipo_gasto_id');
        $sum_mes = static::sumDataReporte($cuentaId, $anno, $tipoMovimientoId, 'mes');

        return compact('datos', 'meses', 'tiposGasto', 'sum_tipo_gasto', 'sum_mes');
    }
}
