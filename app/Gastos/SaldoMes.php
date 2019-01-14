<?php

namespace App\Gastos;

use App\Acl\Usuario;
use App\Gastos\Cuenta;
use App\Gastos\TipoGasto;
use Illuminate\Http\Request;
use App\Gastos\TipoMovimiento;
use Illuminate\Database\Eloquent\Model;

class SaldoMes extends Model
{
    protected $fillable = [
        'cuenta_id', 'anno', 'mes', 'saldo_inicial', 'saldo_final',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'cta_saldos_mes';
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    public static function getSaldoMesAnterior(Request $request)
    {
        $anno = (int) $request->input('anno', 0);
        $mes = (int) $request->input('mes', 0);

        $mesAnterior = ($mes === 1) ? 12 : $mes - 1;
        $annoAnterior = ($mes === 1) ? $anno - 1 : $anno;

        return static::getSaldoFinal($request->input('cuenta_id', 0), $annoAnterior, $mesAnterior);
    }

    public function getSaldoMes(Request $request)
    {
        $saldo = $this->where($request->only('cuenta_id', 'anno', 'mes'))->first();

        if (is_null($saldo)) {
            $saldo = (new static)->fill($request->all());
        }

        return $saldo;
    }

    protected static function getSaldoFinal($cuenta_id = 0, $anno = 0, $mes = 0)
    {
        $saldoMes = static::where(compact('cuenta_id', 'anno', 'mes'))->first();

        if (is_null($saldoMes)) {
            return 0;
        }

        return $saldoMes->saldo_final;
    }

    public function recalculaSaldoMes(Request $request)
    {
        $saldoMes = $this->getSaldoMes($request);
        $saldoMes->saldo_inicial = $this->getSaldoMesAnterior($request);
        $saldoMes->saldo_final = $saldoMes->saldo_inicial + (new Gasto)->getTotalMes($request);

        return $saldoMes->save();
    }
}
