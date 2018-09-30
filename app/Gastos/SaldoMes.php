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

    public function getSaldoMesAnterior(Request $request)
    {
        $anno = (int) $request->input('anno', 0);
        $mes = (int) $request->input('mes', 0);

        $mesAnterior = ($mes === 1) ? 12 : $mes - 1;
        $annoAnterior = ($mes === 1) ? $anno - 1 : $anno;

        return $this->getSaldoFinal($request->input('cuenta_id', 0), $annoAnterior, $mesAnterior);
    }

    public function getSaldoMes(Request $request)
    {
        $saldo = $this->where([
            'cuenta_id' => $request->input('cuenta_id', 0),
            'anno' => $request->input('anno', 0),
            'mes' => $request->input('mes', 0),
        ])->first();

        if (is_null($saldo)) {
            $saldo = (new static)->fill($request->all());
        }

        return $saldo;
    }

    protected function getSaldoFinal($cuenta_id = 0, $anno = 0, $mes = 0)
    {
        $saldoMes = $this->where(compact('cuenta_id', 'anno', 'mes'))->first();

        if (! is_null($saldoMes)) {
            return $saldoMes->saldo_final;
        }

        return 0;
    }

    public function recalculaSaldoMes(Request $request)
    {
        $saldoMes = $this->getSaldoMes($request);
        $saldoMes->saldo_inicial = $this->getSaldoMesAnterior($request);
        $saldoMes->saldo_final = $saldoMes->saldo_inicial + (new Gasto)->getTotalMes($request);

        return $saldoMes->save();
    }
}
