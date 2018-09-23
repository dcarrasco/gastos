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

        if ($mes === 1) {
            $mesAnterior = 12;
            $annoAnterior = $anno - 1;
        }
        else
        {
            $mesAnterior = $mes - 1;
            $annoAnterior = $anno;
        }

        return $this->getSaldo($request->input('cuenta_id', 0), $annoAnterior, $mesAnterior);
    }

    public function getSaldoMes(Request $request)
    {
        return $this->where([
            'cuenta_id' => $request->input('cuenta_id', 0),
            'anno' => $request->input('anno', 0),
            'mes' => $request->input('mes', 0),
        ])->first();
    }

    protected function getSaldo($cuenta_id = 0, $anno = 0, $mes = 0)
    {
        $saldoMes = $this->where(compact('cuenta_id', 'anno', 'mes'))->first();

        if (! is_null($saldoMes)) {
            return $saldoMes->saldo_final;
        }

        return;
    }
}
