<?php

namespace App\Http\Controllers\Gastos;

use Carbon\Carbon;
use App\Gastos\Gasto;
use App\Gastos\Cuenta;
use App\Gastos\SaldoMes;
use App\Gastos\TipoGasto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\AddGastoRequest;

class Ingreso extends Controller
{
    public function showMes(Request $request)
    {
        $today = Carbon::now();
        $cuentaId = $request->input('cuenta_id');
        $anno = $request->input('anno');
        $mes = $request->input('mes');

        if ($request->recalcula === 'recalcula') {
            (new SaldoMes)->recalculaSaldoMes($cuentaId, $anno, $mes);
        }

        return view('gastos.showmes', [
            'today' => $today,
            'selectCuentas' => Cuenta::selectCuentasGastos(),
            'selectTiposGastos' => TipoGasto::formArray(),
            'movimientosMes' => Gasto::movimientosMes($cuentaId, $anno, $mes),
            'saldoMesAnterior' => SaldoMes::getSaldoMesAnterior($cuentaId, $anno, $mes),
        ]);
    }

    public function addGasto(AddGastoRequest $request)
    {
        Gasto::create(array_merge($request->validated(), [
            'tipo_movimiento_id' => TipoGasto::find($request->tipo_gasto_id)->tipo_movimiento_id,
            'usuario_id' => auth()->id(),
        ]));

        $saldoMesAnterior = (new SaldoMes)->recalculaSaldoMes($request->cuenta_id, $request->anno, $request->mes);

        return redirect()->route('gastos.showMes', $request->only([
            'cuenta_id', 'anno', 'mes'
        ]));
    }

    public function borrarGasto(Request $request)
    {
        $gasto = Gasto::findOrFail($request->id)->delete();

        return redirect()->route('gastos.showMes', $request->only([
            'cuenta_id', 'anno', 'mes'
        ]));
    }
}
