<?php

namespace App\Models\Gastos;

use App\Models\Acl\Usuario;
use App\Models\Gastos\Cuenta;
use Illuminate\Support\Carbon;
use App\Models\Gastos\TipoGasto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Gastos\TipoMovimiento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class Gasto extends Model
{
    use HasFactory;

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

    public function hasTipoGasto(): bool
    {
        return ! empty($this->tipo_gasto_id);
    }

    public function getValorMontoAttribute(): int
    {
        return $this->monto * $this->tipoMovimiento->signo;
    }

    public function scopeCuentaAnnoMes(Builder $query, int $cuentaId, int $anno, int $mes): Builder
    {
        return $query->where('cuenta_id', $cuentaId)
            ->where('anno', $anno)
            ->where('mes', $mes);
    }

    public function scopeCuentaAnnoTipMov(Builder $query, int $cuentaId, int $anno, int $tipoMovimientoId): Builder
    {
        return $query->where('cuenta_id', $cuentaId)
            ->where('anno', $anno)
            ->where('tipo_movimiento_id', $tipoMovimientoId);
    }

    public function scopeNoSaldos($query): Builder
    {
        $tipoMovimientoSaldo = 4;

        return $query->where('tipo_movimiento_id', '<>', $tipoMovimientoSaldo); // excluye movimientos de saldos
    }

    public static function movimientosMes(int $cuentaId, int $anno, int $mes): Collection
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

    public function isBeforeDate($date, $dateField = 'fecha'): bool
    {
        return $this->{$dateField} <= $date;
    }

    public static function detalleMovimientosMes(
        int $cuentaId,
        int $anno,
        int $mes,
        int $tipoGastoId
    ): EloquentCollection {
        return static::with('tipoGasto', 'tipoMovimiento')
            ->cuentaAnnoMes($cuentaId, $anno, $mes)
            ->whereTipoGastoId($tipoGastoId)
            ->orderBy('fecha')->orderBy('id')
            ->get();
    }

    public static function movimientosAnno(int $cuentaId, int $anno): EloquentCollection
    {
        return static::with('tipoMovimiento')
            ->where('cuenta_id', $cuentaId)
            ->where('anno', $anno)
            ->noSaldos()
            ->orderBy('fecha')
            ->get();
    }

    public static function saldos(int $cuentaId, int $anno): EloquentCollection
    {
        $tipoMovimientoSaldo = 4;

        return static::cuentaAnnoTipMov($cuentaId, $anno, $tipoMovimientoSaldo)
            ->orderBy('fecha')
            ->get();
    }

    public static function totalMes(int $cuentaId, int $anno, int $mes): int
    {
        return static::movimientosMes($cuentaId, $anno, $mes)
            ->map->valor_monto
            ->sum();
    }

    public static function getDataReporte(int $cuentaId, int $anno, int $tipoMovimientoId): EloquentCollection
    {
        return static::cuentaAnnoTipMov($cuentaId, $anno, $tipoMovimientoId)
            ->select(DB::raw('mes, tipo_gasto_id, sum(monto) as sum_monto'))
            ->groupBy(['mes', 'tipo_gasto_id'])
            ->with('tipoGasto')
            ->get();
    }

    public static function createInversion(array $inversion)
    {
        return static::create(array_merge($inversion, [
            'mes' => Carbon::create($inversion['fecha'])->month,
            'usuario_id' => auth()->id(),
            'tipo_gasto_id' => 0,
        ]));
    }

    public static function createGasto(array $gasto)
    {
        return static::create(array_merge($gasto, [
            'tipo_movimiento_id' => TipoGasto::find($gasto['tipo_gasto_id'])->tipo_movimiento_id,
            'usuario_id' => auth()->id(),
        ]));
    }

    public static function getDataReporteGastosTotales(int $anno): Collection
    {
        return Gasto::whereAnno($anno)
            ->whereIn('cuenta_id', [1,2])
            ->where('tipo_gasto_id', '<>', 3)
            ->whereTipoMovimientoId(1)
            ->with('tipoGasto')
            ->selectRaw('tipo_gasto_id, mes, sum(monto) as monto')
            ->groupBy('tipo_gasto_id', 'mes')
            ->get();
    }

    public function deleteMessage(): string
    {
        return trans('gastos.delete', [
            'gasto' => optional($this->fecha)->format('d-m-Y')
                . ' ' . $this->glosa
                . " " . fmtMonto($this->monto)
            ]);
    }

}
