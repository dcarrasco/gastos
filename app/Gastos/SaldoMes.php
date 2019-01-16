<?php

namespace App\Gastos;

use Carbon\Carbon;
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
        $fechaAnterior = Carbon::create($request->anno, $request->mes, 1)->subMonth();

        return static::firstOrNew([
            'cuenta_id' => $request->cuenta_id,
            'anno' => $fechaAnterior->year,
            'mes' => $fechaAnterior->month,
        ])->saldo_final ?: 0;
    }

    public function recalculaSaldoMes(Request $request)
    {
        $saldoMes = static::firstOrNew($request->only('cuenta_id', 'anno', 'mes'));

        $saldoMes->saldo_inicial = $this->getSaldoMesAnterior($request);
        $saldoMes->saldo_final = $saldoMes->saldo_inicial + Gasto::totalMes($request);

        return $saldoMes->save();
    }
}
